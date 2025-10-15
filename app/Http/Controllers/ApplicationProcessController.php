<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\ApplicationProcesses\DeleteApplicationProcess;
use App\Actions\ApplicationProcesses\StoreApplicationProcess;
use App\Actions\ApplicationProcesses\UpdateApplicationProcess;
use App\Http\Requests\ApplicationProcesses\StoreApplicationProcessRequest;
use App\Http\Requests\ApplicationProcesses\UpdateApplicationProcessRequest;
use App\Models\ApplicationProcess;
use App\Models\RepresentingCountry;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class ApplicationProcessController extends Controller
{
    public function index(): Response
    {
        $processes = ApplicationProcess::query()
            ->with(['parent', 'subProcesses', 'representingCountries.country'])
            ->whereNull('parent_id') // Only main processes
            ->orderBy('order')
            ->get();

        return Inertia::render('application-processes/index', [
            'processes' => $processes,
        ]);
    }

    public function create(): Response
    {
        $parentProcesses = ApplicationProcess::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->get(['id', 'name']);

        $representingCountries = RepresentingCountry::query()
            ->with('country:id,name,flag')
            ->where('is_active', true)
            ->get();

        return Inertia::render('application-processes/create', [
            'parentProcesses' => $parentProcesses,
            'representingCountries' => $representingCountries,
        ]);
    }

    public function store(
        StoreApplicationProcessRequest $request,
        StoreApplicationProcess $storeAction
    ): RedirectResponse {
        $applicationProcess = $storeAction->handle($request->validated());

        // Check if request came from representing countries page
        $referer = $request->header('referer');
        if ($referer && str_contains($referer, 'representing-countries')) {
            return redirect()
                ->route('representing-countries.index')
                ->with('success', 'Application process created successfully.');
        }

        return redirect()
            ->route('application-processes.index')
            ->with('success', 'Application process created successfully.');
    }

    public function show(ApplicationProcess $applicationProcess): Response
    {
        $applicationProcess->load([
            'parent',
            'subProcesses' => fn ($query) => $query->orderBy('order'),
            'representingCountries.country',
        ]);

        return Inertia::render('application-processes/show', [
            'process' => $applicationProcess,
        ]);
    }

    public function edit(ApplicationProcess $applicationProcess): Response
    {
        $applicationProcess->load(['parent', 'representingCountries']);

        $parentProcesses = ApplicationProcess::query()
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->where('id', '!=', $applicationProcess->id) // Exclude itself
            ->orderBy('order')
            ->get(['id', 'name']);

        $representingCountries = RepresentingCountry::query()
            ->with('country:id,name,flag')
            ->where('is_active', true)
            ->get();

        return Inertia::render('application-processes/edit', [
            'process' => $applicationProcess,
            'parentProcesses' => $parentProcesses,
            'representingCountries' => $representingCountries,
        ]);
    }

    public function update(
        UpdateApplicationProcessRequest $request,
        ApplicationProcess $applicationProcess,
        UpdateApplicationProcess $updateAction
    ): RedirectResponse {
        $updatedProcess = $updateAction->handle(
            $applicationProcess,
            $request->validated()
        );

        return redirect()
            ->route('application-processes.show', $updatedProcess)
            ->with('success', 'Application process updated successfully.');
    }

    public function destroy(
        ApplicationProcess $applicationProcess,
        DeleteApplicationProcess $deleteAction
    ): RedirectResponse {
        $deleteAction->handle($applicationProcess);

        return redirect()
            ->route('application-processes.index')
            ->with('success', 'Application process deleted successfully.');
    }
}
