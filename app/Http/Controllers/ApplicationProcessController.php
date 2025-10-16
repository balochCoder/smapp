<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\ApplicationProcesses\DeleteApplicationProcess;
use App\Actions\ApplicationProcesses\StoreApplicationProcess;
use App\Actions\ApplicationProcesses\UpdateApplicationProcess;
use App\Http\Requests\ApplicationProcesses\StoreApplicationProcessRequest;
use App\Http\Requests\ApplicationProcesses\UpdateApplicationProcessRequest;
use App\Models\ApplicationProcess;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class ApplicationProcessController extends Controller
{
    public function index(): Response
    {
        $processes = ApplicationProcess::query()
            ->orderBy('order')
            ->get();

        return Inertia::render('application-processes/index', [
            'processes' => $processes,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('application-processes/create');
    }

    public function store(
        StoreApplicationProcessRequest $request,
        StoreApplicationProcess $storeAction
    ): RedirectResponse {
        $applicationProcess = $storeAction->handle($request->validated());

        return redirect()
            ->route('application-processes.index')
            ->with('success', 'Application process created successfully.');
    }

    public function show(ApplicationProcess $applicationProcess): Response
    {
        return Inertia::render('application-processes/show', [
            'process' => $applicationProcess,
        ]);
    }

    public function edit(ApplicationProcess $applicationProcess): Response
    {
        return Inertia::render('application-processes/edit', [
            'process' => $applicationProcess,
        ]);
    }

    public function update(
        UpdateApplicationProcessRequest $request,
        ApplicationProcess $applicationProcess,
        UpdateApplicationProcess $updateAction
    ): RedirectResponse {
        $updatedApplicationProcess = $updateAction->handle(
            $applicationProcess,
            $request->validated()
        );

        return redirect()
            ->route('application-processes.show', $updatedApplicationProcess)
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
