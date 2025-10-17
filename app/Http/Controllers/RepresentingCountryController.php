<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\RepresentingCountries\AddStatusToRepresentingCountry;
use App\Actions\RepresentingCountries\AddSubStatusToStatus;
use App\Actions\RepresentingCountries\DeleteRepresentingCountry;
use App\Actions\RepresentingCountries\DeleteStatus;
use App\Actions\RepresentingCountries\DeleteSubStatus;
use App\Actions\RepresentingCountries\GetFilteredRepresentingCountries;
use App\Actions\RepresentingCountries\StoreRepresentingCountry;
use App\Actions\RepresentingCountries\UpdateRepresentingCountry;
use App\Actions\RepresentingCountries\UpdateStatusName;
use App\Actions\RepresentingCountries\UpdateStatusNotes;
use App\Actions\RepresentingCountries\UpdateStatusOrder;
use App\Actions\RepresentingCountries\UpdateSubStatus;
use App\Helpers\CurrencyHelper;
use App\Http\Requests\RepresentingCountries\StoreRepresentingCountryRequest;
use App\Http\Requests\RepresentingCountries\UpdateRepresentingCountryRequest;
use App\Http\Resources\RepresentingCountryResource;
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
    public function index(Request $request, GetFilteredRepresentingCountries $getFilteredAction): Response
    {
        $representingCountries = $getFilteredAction->handle(
            $request->input('country'),
            $request->input('status')
        );

        // Get all countries that have representing country records for the filter
        $availableCountries = Country::query()
            ->whereHas('representingCountry')
            ->orderBy('name')
            ->get(['id', 'name', 'flag']);

        // Get statistics (not affected by filters)
        $totalCountries = RepresentingCountry::count();
        $activeCountries = RepresentingCountry::where('is_active', true)->count();

        return Inertia::render('representing-countries/index', [
            'representingCountries' => RepresentingCountryResource::collection($representingCountries),
            'availableCountries' => $availableCountries,
            'filters' => [
                'country' => $request->input('country'),
                'status' => $request->input('status'),
            ],
            'statistics' => [
                'totalCountries' => $totalCountries,
                'activeCountries' => $activeCountries,
            ],
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
            'representingCountry' => new RepresentingCountryResource($representingCountry),
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
                'id' => $process?->id,
                'name' => $status->custom_name ?? $status->status_name,
                'color' => $process?->color ?? 'gray',
            ];
        })->filter(function ($process) {
            return $process['id'] !== null;
        })->values();

        $applicationProcesses = ApplicationProcess::query()
            ->orderBy('order')
            ->get(['id', 'name', 'color']);

        return Inertia::render('representing-countries/edit', [
            'representingCountry' => RepresentingCountryResource::make($representingCountry)->resolve(),
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
            'country',
            'repCountryStatuses' => function ($query) {
                $query->orderBy('order');
            },
        ]);

        return Inertia::render('representing-countries/reorder', [
            'representingCountry' => RepresentingCountryResource::make($representingCountry)->resolve(),
        ]);
    }

    public function updateOrder(
        RepresentingCountry $representingCountry,
        Request $request,
        UpdateStatusOrder $updateOrderAction
    ): RedirectResponse {
        $validated = $request->validate([
            'status_orders' => 'required|array',
            'status_orders.*.id' => 'required|exists:rep_country_status,id',
            'status_orders.*.order' => 'required|integer|min:1',
        ]);

        $updateOrderAction->handle($validated['status_orders']);

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
        Request $request,
        UpdateStatusName $updateStatusNameAction
    ): RedirectResponse {
        $validated = $request->validate([
            'status_id' => 'required|exists:rep_country_status,id',
            'custom_name' => 'required|string|max:255',
        ]);

        $status = RepCountryStatus::find($validated['status_id']);
        if ($status && $status->representing_country_id === $representingCountry->id) {
            $updateStatusNameAction->handle($status, $validated['custom_name']);
        }

        return redirect()
            ->back()
            ->with('success', 'Status name updated successfully.');
    }

    public function notes(RepresentingCountry $representingCountry): Response
    {
        $representingCountry->load([
            'country',
            'repCountryStatuses' => function ($query) {
                $query->orderBy('order');
            },
        ]);

        return Inertia::render('representing-countries/notes', [
            'representingCountry' => RepresentingCountryResource::make($representingCountry)->resolve(),
        ]);
    }

    public function updateNotes(
        RepresentingCountry $representingCountry,
        Request $request,
        UpdateStatusNotes $updateNotesAction
    ): RedirectResponse {
        $validated = $request->validate([
            'status_notes' => 'required|array',
            'status_notes.*.id' => 'required|exists:rep_country_status,id',
            'status_notes.*.notes' => 'nullable|string',
        ]);

        $updateNotesAction->handle($validated['status_notes']);

        return redirect()
            ->route('representing-countries.notes', $representingCountry)
            ->with('success', 'Status notes updated successfully.');
    }

    public function addStatus(
        RepresentingCountry $representingCountry,
        Request $request,
        AddStatusToRepresentingCountry $addStatusAction
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

        $addStatusAction->handle($representingCountry, $validated['status_name']);

        return redirect()
            ->back()
            ->with('success', 'Application process step added successfully.');
    }

    public function addSubStatus(
        RepresentingCountry $representingCountry,
        RepCountryStatus $status,
        Request $request,
        AddSubStatusToStatus $addSubStatusAction
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

        $addSubStatusAction->handle($status, $validated);

        return redirect()
            ->back()
            ->with('success', 'Sub-status added successfully.');
    }

    public function deleteStatus(
        RepresentingCountry $representingCountry,
        RepCountryStatus $status,
        DeleteStatus $deleteStatusAction
    ): RedirectResponse {
        // Verify the status belongs to this representing country
        if ($status->representing_country_id !== $representingCountry->id) {
            abort(404);
        }

        $deleteStatusAction->handle($status);

        return redirect()
            ->back()
            ->with('success', 'Status deleted successfully.');
    }

    public function deleteSubStatus(
        RepresentingCountry $representingCountry,
        RepCountryStatus $status,
        $subStatusId,
        DeleteSubStatus $deleteSubStatusAction
    ): RedirectResponse {
        // Verify the status belongs to this representing country
        if ($status->representing_country_id !== $representingCountry->id) {
            abort(404);
        }

        $subStatus = $status->subStatuses()->findOrFail($subStatusId);
        $deleteSubStatusAction->handle($subStatus);

        return redirect()
            ->back()
            ->with('success', 'Sub-status deleted successfully.');
    }

    public function updateSubStatus(
        RepresentingCountry $representingCountry,
        RepCountryStatus $status,
        $subStatusId,
        Request $request,
        UpdateSubStatus $updateSubStatusAction
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

        $updateSubStatusAction->handle($subStatus, $validated);

        return redirect()
            ->back()
            ->with('success', 'Sub-status updated successfully.');
    }

    public function toggleSubStatusActive(
        RepresentingCountry $representingCountry,
        RepCountryStatus $status,
        $subStatusId
    ): RedirectResponse {
        // Verify the status belongs to this representing country
        if ($status->representing_country_id !== $representingCountry->id) {
            abort(404);
        }

        $subStatus = $status->subStatuses()->findOrFail($subStatusId);
        $subStatus->update([
            'is_active' => ! $subStatus->is_active,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Sub-status updated successfully.');
    }
}
