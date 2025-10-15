<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\RepresentingCountries\DeleteRepresentingCountry;
use App\Actions\RepresentingCountries\StoreRepresentingCountry;
use App\Actions\RepresentingCountries\UpdateRepresentingCountry;
use App\Http\Requests\RepresentingCountries\StoreRepresentingCountryRequest;
use App\Http\Requests\RepresentingCountries\UpdateRepresentingCountryRequest;
use App\Http\Requests\RepresentingCountries\UpdateRepresentingCountryNotesRequest;
use App\Models\ApplicationProcess;
use App\Models\Country;
use App\Models\RepresentingCountry;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class RepresentingCountryController extends Controller
{
    public function index(): Response
    {
        $representingCountries = RepresentingCountry::query()
            ->with(['country', 'applicationProcesses'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('representing-countries/index', [
            'representingCountries' => $representingCountries,
        ]);
    }

    public function create(): Response
    {
        // Get countries that are not already being represented
        $availableCountries = Country::query()
            ->whereDoesntHave('representingCountry')
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'flag']);

        $applicationProcesses = ApplicationProcess::query()
            ->whereNull('parent_id') // Only main processes
            ->where('is_active', true)
            ->orderBy('order')
            ->get(['id', 'name', 'description']);

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
            'applicationProcesses' => function ($query) {
                $query->with('subProcesses')->whereNull('parent_id')->orderBy('order');
            },
        ]);

        return Inertia::render('representing-countries/show', [
            'representingCountry' => $representingCountry,
        ]);
    }

    public function edit(RepresentingCountry $representingCountry): Response
    {
        $representingCountry->load(['country', 'applicationProcesses']);

        $applicationProcesses = ApplicationProcess::query()
            ->whereNull('parent_id') // Only main processes
            ->where('is_active', true)
            ->orderBy('order')
            ->get(['id', 'name', 'description']);

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

    public function notes(RepresentingCountry $representingCountry): Response
    {
        $representingCountry->load([
            'country:id,name,flag',
            'applicationProcesses' => function ($query) {
                $query->whereNull('parent_id')->orderBy('order');
            }
        ]);

        return Inertia::render('representing-countries/notes', [
            'representingCountry' => $representingCountry,
        ]);
    }

    public function updateNotes(
        RepresentingCountry $representingCountry,
        UpdateRepresentingCountryNotesRequest $request
    ): RedirectResponse {
        $validatedData = $request->validated();
        
        // Update descriptions for each application process
        foreach ($validatedData['process_descriptions'] as $processId => $description) {
            ApplicationProcess::where('id', $processId)->update([
                'description' => $description
            ]);
        }

        return redirect()
            ->route('representing-countries.notes', $representingCountry)
            ->with('success', 'Application process descriptions updated successfully.');
    }
}
