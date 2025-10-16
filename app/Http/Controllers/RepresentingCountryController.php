<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\RepresentingCountries\DeleteRepresentingCountry;
use App\Actions\RepresentingCountries\StoreRepresentingCountry;
use App\Actions\RepresentingCountries\UpdateRepresentingCountry;
use App\Helpers\CurrencyHelper;
use App\Http\Requests\RepresentingCountries\StoreRepresentingCountryRequest;
use App\Http\Requests\RepresentingCountries\UpdateRepresentingCountryRequest;
use App\Models\ApplicationProcess;
use App\Models\Country;
use App\Models\RepCountryStatus;
use App\Models\RepresentingCountry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

final class RepresentingCountryController extends Controller
{
    public function index(): Response
    {
        $representingCountries = RepresentingCountry::query()
            ->with([
                'country',
                'repCountryStatuses' => function ($query) {
                    $query->with('subStatuses')->orderBy('order');
                },
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('representing-countries/index', [
            'representingCountries' => $representingCountries,
        ]);
    }

    public function create(): Response
    {
        $availableCountries = Country::query()
            ->whereDoesntHave('representingCountry')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'flag'])
            ->map(function ($country) {
                return [
                    'id' => $country->id,
                    'name' => $country->name,
                    'flag' => $country->flag,
                    'currency' => CurrencyHelper::getCurrencyForCountry($country->name),
                ];
            });

        $applicationProcesses = ApplicationProcess::query()
            ->orderBy('order')
            ->get(['id', 'name', 'color']);

        return Inertia::render('representing-countries/create', [
            'countries' => $availableCountries,
            'applicationProcesses' => $applicationProcesses,
        ]);
    }

    public function store(
        StoreRepresentingCountryRequest $request,
        StoreRepresentingCountry $storeAction
    ): RedirectResponse {
        $representingCountry = $storeAction->handle(
            $request->validated('country_id'),
            $request->validated()
        );

        return redirect()
            ->route('representing-countries.show', $representingCountry)
            ->with('success', 'Representing country created successfully.');
    }

    public function show(RepresentingCountry $representingCountry): Response
    {
        $representingCountry->load([
            'country',
            'repCountryStatuses' => function ($query) {
                $query->with('subStatuses')->orderBy('order');
            },
        ]);

        return Inertia::render('representing-countries/show', [
            'representingCountry' => $representingCountry,
        ]);
    }

    public function edit(RepresentingCountry $representingCountry): Response
    {
        $representingCountry->load([
            'country',
            'repCountryStatuses' => function ($query) {
                $query->orderBy('order');
            },
        ]);

        // Map repCountryStatuses back to application_processes for the form
        $allProcesses = ApplicationProcess::all();
        $representingCountry->application_processes = $representingCountry->repCountryStatuses->map(function ($status) use ($allProcesses) {
            $process = $allProcesses->firstWhere('name', $status->status_name);

            return [
                'id' => $process?->id ?? $status->status_name,
                'name' => $status->custom_name ?? $status->status_name,
                'color' => $process?->color ?? 'gray',
            ];
        });

        $applicationProcesses = ApplicationProcess::query()
            ->orderBy('order')
            ->get(['id', 'name', 'color']);

        return Inertia::render('representing-countries/edit', [
            'representingCountry' => $representingCountry,
            'applicationProcesses' => $applicationProcesses,
        ]);
    }

    public function update(
        UpdateRepresentingCountryRequest $request,
        RepresentingCountry $representingCountry,
        UpdateRepresentingCountry $updateAction
    ): RedirectResponse {
        $updatedRepresentingCountry = $updateAction->handle(
            $representingCountry,
            $request->validated()
        );

        return redirect()
            ->route('representing-countries.show', $updatedRepresentingCountry)
            ->with('success', 'Representing country updated successfully.');
    }

    public function destroy(
        RepresentingCountry $representingCountry,
        DeleteRepresentingCountry $deleteAction
    ): RedirectResponse {
        $deleteAction->handle($representingCountry);

        return redirect()
            ->route('representing-countries.index')
            ->with('success', 'Representing country deleted successfully.');
    }

    public function reorder(RepresentingCountry $representingCountry): Response
    {
        $representingCountry->load([
            'country:id,name,flag',
            'repCountryStatuses' => function ($query) {
                $query->orderBy('order');
            },
        ]);

        return Inertia::render('representing-countries/reorder', [
            'representingCountry' => $representingCountry,
        ]);
    }

    public function updateOrder(
        RepresentingCountry $representingCountry,
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'status_orders' => 'required|array',
            'status_orders.*.id' => 'required|exists:rep_country_status,id',
            'status_orders.*.order' => 'required|integer|min:1',
        ]);

        foreach ($validated['status_orders'] as $statusOrder) {
            $status = RepCountryStatus::find($statusOrder['id']);

            // Prevent reordering the "New" status
            if ($status && $status->status_name === 'New') {
                continue;
            }

            RepCountryStatus::where('id', $statusOrder['id'])->update([
                'order' => $statusOrder['order'],
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Status order updated successfully.');
    }

    public function toggleActive(RepresentingCountry $representingCountry): RedirectResponse
    {
        $representingCountry->update([
            'is_active' => ! $representingCountry->is_active,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Representing country status updated successfully.');
    }

    public function toggleStatusActive(
        RepresentingCountry $representingCountry,
        Request $request
    ): RedirectResponse {
        $request->validate([
            'status_id' => 'required|exists:rep_country_status,id',
        ]);

        $statusId = $request->input('status_id');

        $status = RepCountryStatus::find($statusId);
        if ($status && $status->representing_country_id === $representingCountry->id) {
            $status->update([
                'is_active' => ! $status->is_active,
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Status updated successfully.');
    }

    public function updateStatusName(
        RepresentingCountry $representingCountry,
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'status_id' => 'required|exists:rep_country_status,id',
            'custom_name' => 'required|string|max:255',
        ]);

        $status = RepCountryStatus::find($validated['status_id']);
        if ($status && $status->representing_country_id === $representingCountry->id) {
            $status->update([
                'custom_name' => $validated['custom_name'],
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Status name updated successfully.');
    }

    public function notes(RepresentingCountry $representingCountry): Response
    {
        $representingCountry->load([
            'country:id,name,flag',
            'repCountryStatuses' => function ($query) {
                $query->orderBy('order');
            },
        ]);

        return Inertia::render('representing-countries/notes', [
            'representingCountry' => $representingCountry,
        ]);
    }

    public function updateNotes(
        RepresentingCountry $representingCountry,
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'status_notes' => 'required|array',
            'status_notes.*.id' => 'required|exists:rep_country_status,id',
            'status_notes.*.notes' => 'nullable|string',
        ]);

        foreach ($validated['status_notes'] as $statusNote) {
            RepCountryStatus::where('id', $statusNote['id'])->update([
                'notes' => $statusNote['notes'],
            ]);
        }

        return redirect()
            ->route('representing-countries.notes', $representingCountry)
            ->with('success', 'Status notes updated successfully.');
    }

    public function addStatus(
        RepresentingCountry $representingCountry,
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'status_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rep_country_status', 'status_name')
                    ->where('representing_country_id', $representingCountry->id),
            ],
        ], [
            'status_name.unique' => 'This status already exists for this country.',
        ]);

        // Get the max order for this representing country
        $maxOrder = $representingCountry->repCountryStatuses()->max('order') ?? 0;

        RepCountryStatus::create([
            'representing_country_id' => $representingCountry->id,
            'status_name' => $validated['status_name'],
            'order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Application process step added successfully.');
    }

    public function addSubStatus(
        RepresentingCountry $representingCountry,
        RepCountryStatus $status,
        Request $request
    ): RedirectResponse {
        // Verify the status belongs to this representing country
        if ($status->representing_country_id !== $representingCountry->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_statuses', 'name')
                    ->where('rep_country_status_id', $status->id),
            ],
            'description' => 'nullable|string|max:1000',
        ], [
            'name.unique' => 'This sub-status already exists for this status.',
        ]);

        // Get the max order for this status's sub-statuses
        $maxOrder = $status->subStatuses()->max('order') ?? 0;

        $status->subStatuses()->create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'order' => $maxOrder + 1,
            'is_active' => true,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Sub-status added successfully.');
    }

    public function deleteStatus(
        RepresentingCountry $representingCountry,
        RepCountryStatus $status
    ): RedirectResponse {
        // Verify the status belongs to this representing country
        if ($status->representing_country_id !== $representingCountry->id) {
            abort(404);
        }

        // Soft delete all sub-statuses first
        $status->subStatuses()->delete();

        // Then soft delete the status
        $status->delete();

        return redirect()
            ->back()
            ->with('success', 'Status deleted successfully.');
    }

    public function deleteSubStatus(
        RepresentingCountry $representingCountry,
        RepCountryStatus $status,
        $subStatusId
    ): RedirectResponse {
        // Verify the status belongs to this representing country
        if ($status->representing_country_id !== $representingCountry->id) {
            abort(404);
        }

        $subStatus = $status->subStatuses()->findOrFail($subStatusId);
        $subStatus->delete();

        return redirect()
            ->back()
            ->with('success', 'Sub-status deleted successfully.');
    }

    public function updateSubStatus(
        RepresentingCountry $representingCountry,
        RepCountryStatus $status,
        $subStatusId,
        Request $request
    ): RedirectResponse {
        // Verify the status belongs to this representing country
        if ($status->representing_country_id !== $representingCountry->id) {
            abort(404);
        }

        $subStatus = $status->subStatuses()->findOrFail($subStatusId);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_statuses', 'name')
                    ->where('rep_country_status_id', $status->id)
                    ->ignore($subStatus->id),
            ],
            'description' => 'nullable|string|max:1000',
        ], [
            'name.unique' => 'This sub-status name already exists for this status.',
        ]);

        $subStatus->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Sub-status updated successfully.');
    }
}
