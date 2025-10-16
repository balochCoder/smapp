import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Switch } from '@/components/ui/switch';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/react';
import {
    PlusIcon,
    FileText,
    Calendar,
    ArrowUpDown,
    ChevronDown,
    Pencil,
    Copy,
    List,
    Trash2,
    Eye,
    Edit,
} from 'lucide-react';
import { dashboard } from '@/routes';
import * as representingCountries from '@/routes/representing-countries';
import { useState } from 'react';
import { useDialog } from '@/hooks/use-dialog';

interface Country {
    id: string;
    name: string;
    flag: string;
}

interface SubStatus {
    id: number;
    name: string;
    description: string | null;
    order: number;
    is_active: boolean;
}

interface RepCountryStatus {
    id: number;
    status_name: string;
    custom_name: string | null;
    notes: string | null;
    order: number;
    is_active: boolean;
    sub_statuses?: SubStatus[];
}

interface RepresentingCountry {
    id: string;
    monthly_living_cost: string | null;
    currency?: string;
    is_active: boolean;
    created_at: string;
    country: Country;
    rep_country_statuses: RepCountryStatus[];
}

interface PaginatedData {
    data: RepresentingCountry[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

interface Props {
    representingCountries: PaginatedData;
}

interface StatusDialogData {
    representingCountry: RepresentingCountry;
    status?: RepCountryStatus;
}

interface SubStatusSheetData {
    representingCountry: RepresentingCountry;
    status: RepCountryStatus;
}

interface AddStepDialogData {
    representingCountry: RepresentingCountry;
}

interface AddSubStatusDialogData {
    representingCountry: RepresentingCountry;
    status: RepCountryStatus;
}

interface EditSubStatusDialogData {
    representingCountry: RepresentingCountry;
    status: RepCountryStatus;
    subStatus: SubStatus;
}

interface DeleteStatusData {
    representingCountryId: string;
    status: RepCountryStatus;
}

interface DeleteSubStatusData {
    representingCountry: RepresentingCountry;
    status: RepCountryStatus;
    subStatus: SubStatus;
}

interface DeleteRepCountryData {
    id: string;
    countryName: string;
}

interface ApplicationProcess {
    id: string;
    name: string;
    color: string;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Representing Countries',
        href: representingCountries.index().url,
    },
];

export default function Index({ representingCountries: data }: Props) {
    const [expandedCountries, setExpandedCountries] = useState<Set<string>>(
        new Set()
    );
    const [isSheetClosing, setIsSheetClosing] = useState(false);

    const statusDialog = useDialog<StatusDialogData>();
    const subStatusSheet = useDialog<SubStatusSheetData>();
    const addStepDialog = useDialog<AddStepDialogData>();
    const addSubStatusDialog = useDialog<AddSubStatusDialogData>();
    const editSubStatusDialog = useDialog<EditSubStatusDialogData>();
    const deleteStatusAlert = useDialog<DeleteStatusData>();
    const deleteSubStatusAlert = useDialog<DeleteSubStatusData>();
    const deleteRepCountryAlert = useDialog<DeleteRepCountryData>();

    const { data: formData, setData, put, processing, errors, reset } = useForm({
        status_id: 0,
        custom_name: '',
    });

    const { data: addStepFormData, setData: setAddStepData, post, processing: addingStep, errors: addStepErrors, reset: resetAddStep } = useForm({
        status_name: '',
    });

    const { data: addSubStatusFormData, setData: setAddSubStatusData, post: postSubStatus, processing: addingSubStatus, errors: addSubStatusErrors, reset: resetAddSubStatus } = useForm({
        name: '',
        description: '',
    });

    const { data: editSubStatusFormData, setData: setEditSubStatusData, put: putSubStatus, processing: editingSubStatus, errors: editSubStatusErrors, reset: resetEditSubStatus } = useForm({
        name: '',
        description: '',
    });

    const handleDelete = (id: string, countryName: string) => {
        deleteRepCountryAlert.open({ id, countryName });
    };

    const confirmDeleteRepCountry = () => {
        if (!deleteRepCountryAlert.data) return;

        router.delete(representingCountries.destroy(deleteRepCountryAlert.data.id).url, {
            onSuccess: () => {
                deleteRepCountryAlert.close();
            },
        });
    };

    const handleToggleActive = (id: string) => {
        router.post(
            representingCountries.toggleActive(id).url,
            {},
            {
                preserveScroll: true,
            }
        );
    };

    const handleToggleStatusActive = (
        representingCountryId: string,
        statusId: number
    ) => {
        router.post(
            representingCountries.toggleStatusActive(representingCountryId).url,
            {
                status_id: statusId,
            },
            {
                preserveScroll: true,
            }
        );
    };

    const toggleCountryExpansion = (countryId: string) => {
        const newExpanded = new Set(expandedCountries);
        if (newExpanded.has(countryId)) {
            newExpanded.delete(countryId);
        } else {
            newExpanded.add(countryId);
        }
        setExpandedCountries(newExpanded);
    };

    const handleEditStatus = (
        repCountry: RepresentingCountry,
        status: RepCountryStatus
    ) => {
        setData({
            status_id: status.id,
            custom_name: status.custom_name || status.status_name,
        });
        statusDialog.open({ representingCountry: repCountry, status });
    };

    const handleViewSubStatuses = (
        repCountry: RepresentingCountry,
        status: RepCountryStatus
    ) => {
        subStatusSheet.open({ representingCountry: repCountry, status });
    };

    const handleAddSubStatus = (
        repCountry: RepresentingCountry,
        status: RepCountryStatus
    ) => {
        setAddSubStatusData({
            name: '',
            description: '',
        });
        addSubStatusDialog.open({ representingCountry: repCountry, status });
    };

    const handleSubmitAddSubStatus = (e: React.FormEvent) => {
        e.preventDefault();
        if (!addSubStatusDialog.data) return;

        postSubStatus(
            representingCountries.addSubstatus([
                addSubStatusDialog.data.representingCountry.id,
                addSubStatusDialog.data.status.id,
            ]).url,
            {
                preserveScroll: true,
                onSuccess: () => {
                    addSubStatusDialog.close();
                    resetAddSubStatus();

                    // Update the sheet data in real-time
                    if (subStatusSheet.data && subStatusSheet.data.status.id === addSubStatusDialog.data!.status.id) {
                        const currentSubStatuses = subStatusSheet.data.status.sub_statuses || [];
                        const newSubStatus: SubStatus = {
                            id: Date.now(), // Temporary ID until reload
                            name: addSubStatusFormData.name,
                            description: addSubStatusFormData.description || null,
                            order: currentSubStatuses.length + 1,
                            is_active: true,
                        };

                        subStatusSheet.open({
                            ...subStatusSheet.data,
                            status: {
                                ...subStatusSheet.data.status,
                                sub_statuses: [...currentSubStatuses, newSubStatus],
                            },
                        });
                    }

                    router.reload({ only: ['representingCountries'] });
                },
            }
        );
    };

    const handleEditSubStatus = (
        repCountry: RepresentingCountry,
        status: RepCountryStatus,
        subStatus: SubStatus
    ) => {
        setEditSubStatusData({
            name: subStatus.name,
            description: subStatus.description || '',
        });
        editSubStatusDialog.open({
            representingCountry: repCountry,
            status,
            subStatus,
        });
    };

    const handleSubmitEditSubStatus = (e: React.FormEvent) => {
        e.preventDefault();
        if (!editSubStatusDialog.data) return;

        putSubStatus(
            representingCountries.updateSubstatus([
                editSubStatusDialog.data.representingCountry.id,
                editSubStatusDialog.data.status.id,
                editSubStatusDialog.data.subStatus.id,
            ]).url,
            {
                preserveScroll: true,
                onSuccess: () => {
                    editSubStatusDialog.close();
                    resetEditSubStatus();

                    // Update the sheet data in real-time
                    if (subStatusSheet.data) {
                        const updatedSubStatuses = subStatusSheet.data.status.sub_statuses?.map(
                            (ss) =>
                                ss.id === editSubStatusDialog.data!.subStatus.id
                                    ? {
                                          ...ss,
                                          name: editSubStatusFormData.name,
                                          description: editSubStatusFormData.description || null,
                                      }
                                    : ss
                        );

                        subStatusSheet.open({
                            ...subStatusSheet.data,
                            status: {
                                ...subStatusSheet.data.status,
                                sub_statuses: updatedSubStatuses,
                            },
                        });
                    }

                    router.reload({ only: ['representingCountries'] });
                },
            }
        );
    };

    const handleDeleteStatus = (
        representingCountryId: string,
        status: RepCountryStatus
    ) => {
        deleteStatusAlert.open({ representingCountryId, status });
    };

    const confirmDeleteStatus = () => {
        if (!deleteStatusAlert.data) return;

        router.delete(
            representingCountries.deleteStatus([
                deleteStatusAlert.data.representingCountryId,
                deleteStatusAlert.data.status.id,
            ]).url,
            {
                preserveScroll: true,
                onSuccess: () => {
                    deleteStatusAlert.close();
                },
            }
        );
    };

    const handleDeleteSubStatus = (
        repCountry: RepresentingCountry,
        status: RepCountryStatus,
        subStatus: SubStatus
    ) => {
        deleteSubStatusAlert.open({ representingCountry: repCountry, status, subStatus });
    };

    const confirmDeleteSubStatus = () => {
        if (!deleteSubStatusAlert.data) return;

        router.delete(
            representingCountries.deleteSubstatus([
                deleteSubStatusAlert.data.representingCountry.id,
                deleteSubStatusAlert.data.status.id,
                deleteSubStatusAlert.data.subStatus.id,
            ]).url,
            {
                preserveScroll: true,
                onSuccess: () => {
                    // Update the sheet data in real-time
                    if (subStatusSheet.data) {
                        const updatedSubStatuses = subStatusSheet.data.status.sub_statuses?.filter(
                            (ss) => ss.id !== deleteSubStatusAlert.data!.subStatus.id
                        );

                        subStatusSheet.open({
                            ...subStatusSheet.data,
                            status: {
                                ...subStatusSheet.data.status,
                                sub_statuses: updatedSubStatuses,
                            },
                        });
                    }

                    deleteSubStatusAlert.close();
                    router.reload({ only: ['representingCountries'] });
                },
            }
        );
    };

    const handleAddStep = (repCountry: RepresentingCountry) => {
        setAddStepData('status_name', '');
        addStepDialog.open({ representingCountry: repCountry });
    };

    const handleSubmitAddStep = (e: React.FormEvent) => {
        e.preventDefault();
        if (!addStepDialog.data) return;

        post(
            representingCountries.addStatus(addStepDialog.data.representingCountry.id).url,
            {
                preserveScroll: true,
                onSuccess: () => {
                    addStepDialog.close();
                    resetAddStep();
                    router.reload({ only: ['representingCountries'] });
                },
            }
        );
    };

    const handleSubmitStatus = (e: React.FormEvent) => {
        e.preventDefault();
        if (!statusDialog.data) return;

        put(
            representingCountries.updateStatusName(
                statusDialog.data.representingCountry.id
            ).url,
            {
                preserveScroll: true,
            onSuccess: () => {
                    statusDialog.close();
                reset();
                router.reload({ only: ['representingCountries'] });
            },
            }
        );
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-GB', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Representing Countries" />

            <div className="flex h-full flex-1 flex-col gap-4 p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight">
                            Representing Countries
                        </h1>
                        <p className="text-muted-foreground">
                            Manage countries your organization represents for
                            study abroad programs
                        </p>
                    </div>
                    <Link href={representingCountries.create()}>
                        <Button>
                            <PlusIcon className="mr-2 h-4 w-4" />
                            Add Country
                        </Button>
                    </Link>
                </div>

                <div className="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    {data.data.map((repCountry) => {
                        const isExpanded = expandedCountries.has(
                            repCountry.id
                        );
                        const visibleStatuses = isExpanded
                            ? repCountry.rep_country_statuses
                            : repCountry.rep_country_statuses.slice(0, 3);
                        const remainingCount =
                            repCountry.rep_country_statuses.length - 3;

                        return (
                            <Card
                                key={repCountry.id}
                                className="overflow-hidden"
                            >
                                <CardHeader className="pb-3 px-4 pt-4">
                                    <div className="flex items-start gap-2">
                                        {repCountry.country.flag && (
                                            <img
                                                src={repCountry.country.flag}
                                                alt={repCountry.country.name}
                                                className="h-6 w-8 rounded object-cover border"
                                            />
                                        )}
                                        <div className="flex-1 min-w-0">
                                            <CardTitle className="text-sm font-semibold truncate">
                                                {repCountry.country.name}
                                            </CardTitle>
                                            <div className="flex items-center gap-1.5 mt-1">
                                                <Switch
                                                    checked={
                                                        repCountry.is_active
                                                    }
                                                    onCheckedChange={() =>
                                                        handleToggleActive(
                                                            repCountry.id
                                                        )
                                                    }
                                                />
                                                <span
                                                    className={`text-xs font-medium ${
                                                    repCountry.is_active
                                                        ? 'text-blue-600'
                                                        : 'text-gray-500'
                                                    }`}
                                                >
                                                    {repCountry.is_active
                                                        ? 'Active'
                                                        : 'Inactive'}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </CardHeader>

                                <CardContent className="px-4 pb-4 space-y-3">
                                    <div className="space-y-1">
                                        <div className="flex items-center gap-1.5 text-xs text-gray-600">
                                            <FileText className="h-3 w-3" />
                                            <span>
                                                {
                                                    repCountry
                                                        .rep_country_statuses
                                                        .length
                                                }{' '}
                                                statuses
                                            </span>
                                        </div>
                                        <div className="flex items-center gap-1.5 text-xs text-gray-600">
                                            <Calendar className="h-3 w-3" />
                                            <span>
                                                Added:{' '}
                                                {formatDate(
                                                    repCountry.created_at
                                                )}
                                            </span>
                                        </div>
                                       
                                    </div>

                                    <div className="space-y-1">
                                        <div className="flex gap-1">
                                            <Link
                                                href={representingCountries.show(
                                                    repCountry.id
                                                )}
                                                className="flex-1"
                                            >
                                                <Button
                                                    size="sm"
                                                    variant="outline"
                                                    className="w-full h-7 text-xs px-2"
                                                >
                                                    <Eye className="mr-1 h-3 w-3" />
                                                    View
                                                </Button>
                                            </Link>
                                            <Link
                                                href={representingCountries.edit(
                                                    repCountry.id
                                                )}
                                                className="flex-1"
                                            >
                                                <Button
                                                    size="sm"
                                                    variant="outline"
                                                    className="w-full h-7 text-xs px-2"
                                                >
                                                    <Edit className="mr-1 h-3 w-3" />
                                                    Edit
                                                </Button>
                                            </Link>
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                className="flex-1 h-7 text-xs px-2 text-red-600 hover:text-red-700 hover:border-red-600"
                                                onClick={() => handleDelete(repCountry.id, repCountry.country.name)}
                                            >
                                                <Trash2 className="mr-1 h-3 w-3" />
                                                Delete
                                            </Button>
                                        </div>
                                        <div className="flex gap-1">
                                            <Link
                                                href={representingCountries.notes(
                                                    repCountry.id
                                                )}
                                                className="flex-1"
                                            >
                                                <Button
                                                    size="sm"
                                                    variant="outline"
                                                    className="w-full h-7 text-xs px-2"
                                                >
                                                    <FileText className="mr-1 h-3 w-3" />
                                                    Notes
                                                </Button>
                                            </Link>
                                            <Link
                                                href={representingCountries.reorder(
                                                    repCountry.id
                                                )}
                                                className="flex-1"
                                            >
                                                <Button
                                                    size="sm"
                                                    variant="outline"
                                                    className="w-full h-7 text-xs px-2"
                                                >
                                                <ArrowUpDown className="mr-1 h-3 w-3" />
                                                Reorder
                                            </Button>
                                            </Link>
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                className="flex-1 h-7 text-xs px-2"
                                                onClick={() => handleAddStep(repCountry)}
                                            >
                                                <PlusIcon className="mr-1 h-3 w-3" />
                                                Add Step
                                            </Button>
                                        </div>
                                    </div>

                                    <div className="border-t pt-3">
                                        <div className="flex items-center justify-between mb-2">
                                            <span className="text-xs font-medium">
                                                Statuses:
                                            </span>
                                            {repCountry.rep_country_statuses
                                                .length > 3 && (
                                                <Button
                                                    variant="ghost"
                                                    size="sm"
                                                    onClick={() =>
                                                        toggleCountryExpansion(
                                                            repCountry.id
                                                        )
                                                    }
                                                    className="h-5 px-1.5 text-xs"
                                                >
                                                    View All (
                                                    {
                                                        repCountry
                                                            .rep_country_statuses
                                                            .length
                                                    }
                                                    )
                                                    <ChevronDown
                                                        className={`ml-1 h-2.5 w-2.5 transition-transform ${
                                                            isExpanded
                                                                ? 'rotate-180'
                                                                : ''
                                                        }`}
                                                    />
                                                </Button>
                                            )}
                                        </div>

                                        <div className="space-y-1.5">
                                            {visibleStatuses.map(
                                                (status, index) => {
                                                    const isNewStatus = status.status_name === 'New';

                                                    return (
                                                    <div
                                                        key={status.id}
                                                        className="flex items-center gap-2 p-1.5 bg-gray-50 rounded"
                                                    >
                                                        <div className="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/10 text-xs font-semibold text-primary">
                                                            {index + 1}
                                                        </div>
                                                        <span className="text-xs font-medium truncate flex-1 mr-2">
                                                            {status.custom_name ||
                                                                status.status_name}
                                                        </span>
                                                    <div className="flex items-center gap-1">
                                                        {!isNewStatus ? (
                                                            <>
                                                        <Switch
                                                                    checked={
                                                                        status.is_active
                                                                    }
                                                                    onCheckedChange={() =>
                                                                        handleToggleStatusActive(
                                                                            repCountry.id,
                                                                            status.id
                                                                        )
                                                                    }
                                                        />
                                                        <div className="flex items-center gap-1 ml-2">
                                                                    <Button
                                                                        variant="ghost"
                                                                        size="sm"
                                                                        className="h-5 w-5 p-0"
                                                                        onClick={() =>
                                                                            handleEditStatus(
                                                                                repCountry,
                                                                                status
                                                                            )
                                                                        }
                                                                    >
                                                                <Pencil className="h-2.5 w-2.5" />
                                                            </Button>
                                                                    <Button
                                                                        variant="ghost"
                                                                        size="sm"
                                                                        className="h-5 w-5 p-0"
                                                                        onClick={() =>
                                                                            handleAddSubStatus(
                                                                                repCountry,
                                                                                status
                                                                            )
                                                                        }
                                                                    >
                                                                        <PlusIcon className="h-2.5 w-2.5" />
                                                                    </Button>
                                                                    <Button
                                                                        variant="ghost"
                                                                        size="sm"
                                                                        className="h-5 w-5 p-0"
                                                                        onClick={() =>
                                                                            handleViewSubStatuses(
                                                                                repCountry,
                                                                                status
                                                                            )
                                                                        }
                                                                    >
                                                                        <List className="h-2.5 w-2.5" />
                                                            </Button>
                                                                    <Button
                                                                        variant="ghost"
                                                                        size="sm"
                                                                        className="h-5 w-5 p-0 text-red-600 hover:text-red-700"
                                                                        onClick={() =>
                                                                            handleDeleteStatus(
                                                                                repCountry.id,
                                                                                status
                                                                            )
                                                                        }
                                                                    >
                                                                <Trash2 className="h-2.5 w-2.5" />
                                                            </Button>
                                                        </div>
                                                            </>
                                                        ) : (
                                                            <Badge variant="secondary" className="text-xs">
                                                                System Status
                                                            </Badge>
                                                        )}
                                                    </div>
                                                </div>
                                                );
                                                }
                                            )}

                                            {!isExpanded && remainingCount > 0 && (
                                                <div className="text-center py-1">
                                                    <span className="text-xs text-gray-500">
                                                        +{remainingCount} more
                                                    </span>
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        );
                    })}
                </div>

                {data.data.length === 0 && (
                    <Card>
                        <CardContent className="flex flex-col items-center justify-center py-12">
                            <p className="text-muted-foreground mb-4">
                                No representing countries added yet
                            </p>
                            <Link href={representingCountries.create()}>
                                <Button>
                                    <PlusIcon className="mr-2 h-4 w-4" />
                                    Add Your First Country
                                </Button>
                            </Link>
                        </CardContent>
                    </Card>
                )}

                {/* Sub-Status Sheet */}
                <Sheet
                    open={subStatusSheet.isOpen && !isSheetClosing}
                    onOpenChange={(open) => {
                        if (!open) {
                            // Start closing animation
                            setIsSheetClosing(true);
                            // Clear data after animation completes
                            setTimeout(() => {
                                subStatusSheet.close();
                                setIsSheetClosing(false);
                            }, 300);
                        }
                    }}
                >
                    <SheetContent>
                        {(subStatusSheet.isOpen || isSheetClosing) && subStatusSheet.data && (
                            <>
                                <SheetHeader>
                                    <SheetTitle>
                                        Sub-Statuses for{' '}
                                        {subStatusSheet.data.status.custom_name ||
                                            subStatusSheet.data.status.status_name}
                                    </SheetTitle>
                                    <SheetDescription>
                                        Viewing sub-statuses for{' '}
                                        {subStatusSheet.data.representingCountry.country.name}
                                    </SheetDescription>
                                </SheetHeader>
                                <div className="mt-6 space-y-4">
                                    {subStatusSheet.data.status.sub_statuses &&
                                    subStatusSheet.data.status.sub_statuses.length > 0 ? (
                                <div className="space-y-2">
                                    {subStatusSheet.data.status.sub_statuses.map(
                                        (subStatus, index) => (
                                            <div
                                                key={subStatus.id}
                                                className="flex items-center gap-3 rounded-lg border bg-card p-3"
                                            >
                                                <div className="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-primary/10 text-xs font-semibold text-primary">
                                                    {index + 1}
                                                </div>
                                                <div className="flex-1">
                                                    <p className="font-medium text-sm">
                                                        {subStatus.name}
                                                    </p>
                                                    {subStatus.description && (
                                                        <p className="text-xs text-muted-foreground">
                                                            {
                                                                subStatus.description
                                                            }
                                                        </p>
                                                    )}
                                                </div>
                                                <div className="flex items-center gap-2">
                                                    <Badge
                                                        variant={
                                                            subStatus.is_active
                                                                ? 'default'
                                                                : 'secondary'
                                                        }
                                                    >
                                                        {subStatus.is_active
                                                            ? 'Active'
                                                            : 'Inactive'}
                                                    </Badge>
                                                    <Button
                                                        variant="ghost"
                                                        size="sm"
                                                        className="h-7 w-7 p-0"
                                                        onClick={() =>
                                                            handleEditSubStatus(
                                                                subStatusSheet.data!.representingCountry,
                                                                subStatusSheet.data!.status,
                                                                subStatus
                                                            )
                                                        }
                                                    >
                                                        <Pencil className="h-3.5 w-3.5" />
                                                    </Button>
                                                    <Button
                                                        variant="ghost"
                                                        size="sm"
                                                        className="h-7 w-7 p-0 text-red-600 hover:text-red-700"
                                                        onClick={() =>
                                                            handleDeleteSubStatus(
                                                                subStatusSheet.data!.representingCountry,
                                                                subStatusSheet.data!.status,
                                                                subStatus
                                                            )
                                                        }
                                                    >
                                                        <Trash2 className="h-3.5 w-3.5" />
                                                    </Button>
                                                </div>
                                            </div>
                                        )
                                    )}
                                </div>
                            ) : (
                                        <div className="flex flex-col items-center justify-center py-12 text-center">
                                            <p className="text-muted-foreground mb-4">
                                                No sub-statuses added yet
                                            </p>
                                            <Button>
                                                <PlusIcon className="mr-2 h-4 w-4" />
                                                Add Sub-Status
                                            </Button>
                                        </div>
                                    )}
                                </div>
                            </>
                        )}
                    </SheetContent>
                </Sheet>

                {/* Edit Sub-Status Dialog */}
                <Dialog
                    open={editSubStatusDialog.isOpen}
                    onOpenChange={(open) => {
                        if (!open) {
                            setTimeout(() => {
                                editSubStatusDialog.close();
                                resetEditSubStatus();
                            }, 200);
                        }
                    }}
                >
                    <DialogContent className="sm:max-w-[425px]">
                        <DialogHeader>
                            <DialogTitle>Edit Sub-Status</DialogTitle>
                            <DialogDescription>
                                Edit sub-status for{' '}
                                {editSubStatusDialog.data?.status.custom_name ||
                                    editSubStatusDialog.data?.status.status_name}{' '}
                                in{' '}
                                {
                                    editSubStatusDialog.data?.representingCountry
                                        .country.name
                                }
                            </DialogDescription>
                        </DialogHeader>
                        <form onSubmit={handleSubmitEditSubStatus} className="space-y-4">
                            <div className="space-y-2">
                                <Label htmlFor="edit-substatus-name">
                                    Sub-Status Name
                                </Label>
                                <Input
                                    id="edit-substatus-name"
                                    value={editSubStatusFormData.name}
                                    onChange={(e) =>
                                        setEditSubStatusData('name', e.target.value)
                                    }
                                    placeholder="Enter sub-status name"
                                    required
                                />
                                {editSubStatusErrors.name && (
                                    <p className="text-sm text-red-600">
                                        {editSubStatusErrors.name}
                                    </p>
                                )}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="edit-substatus-description">
                                    Description (Optional)
                                </Label>
                                <Textarea
                                    id="edit-substatus-description"
                                    value={editSubStatusFormData.description}
                                    onChange={(e) =>
                                        setEditSubStatusData('description', e.target.value)
                                    }
                                    placeholder="Enter description..."
                                    rows={3}
                                />
                                {editSubStatusErrors.description && (
                                    <p className="text-sm text-red-600">
                                        {editSubStatusErrors.description}
                                    </p>
                                )}
                            </div>

                            <DialogFooter>
                                <Button
                                    type="button"
                                    variant="outline"
                                    onClick={() => {
                                        // Trigger the dialog close animation
                                        const dialog = document.querySelector('[role="dialog"]');
                                        if (dialog) {
                                            dialog.setAttribute('data-state', 'closed');
                                        }
                                        setTimeout(() => {
                                            editSubStatusDialog.close();
                                            resetEditSubStatus();
                                        }, 200);
                                    }}
                                >
                                    Cancel
                                </Button>
                                <Button type="submit" disabled={editingSubStatus}>
                                    {editingSubStatus ? 'Updating...' : 'Update Sub-Status'}
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>

                {/* Add Sub-Status Dialog */}
                <Dialog
                    open={addSubStatusDialog.isOpen}
                    onOpenChange={(open) => {
                        if (!open) {
                            setTimeout(() => {
                                addSubStatusDialog.close();
                                resetAddSubStatus();
                            }, 200);
                        }
                    }}
                >
                    <DialogContent className="sm:max-w-[425px]">
                        <DialogHeader>
                            <DialogTitle>Add Sub-Status</DialogTitle>
                            <DialogDescription>
                                Add a sub-status for{' '}
                                {addSubStatusDialog.data?.status.custom_name ||
                                    addSubStatusDialog.data?.status.status_name}{' '}
                                in{' '}
                                {
                                    addSubStatusDialog.data?.representingCountry
                                        .country.name
                                }
                            </DialogDescription>
                        </DialogHeader>
                        <form onSubmit={handleSubmitAddSubStatus} className="space-y-4">
                            <div className="space-y-2">
                                <Label htmlFor="substatus-name">
                                    Sub-Status Name
                                </Label>
                                <Input
                                    id="substatus-name"
                                    value={addSubStatusFormData.name}
                                    onChange={(e) =>
                                        setAddSubStatusData('name', e.target.value)
                                    }
                                    placeholder="Enter sub-status name"
                                    required
                                />
                                {addSubStatusErrors.name && (
                                    <p className="text-sm text-red-600">
                                        {addSubStatusErrors.name}
                                    </p>
                                )}
                            </div>

                            <div className="space-y-2">
                                <Label htmlFor="substatus-description">
                                    Description (Optional)
                                </Label>
                                <Textarea
                                    id="substatus-description"
                                    value={addSubStatusFormData.description}
                                    onChange={(e) =>
                                        setAddSubStatusData('description', e.target.value)
                                    }
                                    placeholder="Enter description..."
                                    rows={3}
                                />
                                {addSubStatusErrors.description && (
                                    <p className="text-sm text-red-600">
                                        {addSubStatusErrors.description}
                                    </p>
                                )}
                            </div>

                            <DialogFooter>
                                <Button
                                    type="button"
                                    variant="outline"
                                    onClick={() => {
                                        const dialog = document.querySelector('[role="dialog"]');
                                        if (dialog) {
                                            dialog.setAttribute('data-state', 'closed');
                                        }
                                        setTimeout(() => {
                                            addSubStatusDialog.close();
                                            resetAddSubStatus();
                                        }, 200);
                                    }}
                                >
                                    Cancel
                                </Button>
                                <Button type="submit" disabled={addingSubStatus}>
                                    {addingSubStatus ? 'Adding...' : 'Add Sub-Status'}
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>

                {/* Add Step Dialog */}
                <Dialog
                    open={addStepDialog.isOpen}
                    onOpenChange={(open) => {
                        if (!open) {
                            setTimeout(() => {
                                addStepDialog.close();
                                resetAddStep();
                            }, 200);
                        }
                    }}
                >
                    <DialogContent className="sm:max-w-[425px]">
                        <DialogHeader>
                            <DialogTitle>Add New Step</DialogTitle>
                            <DialogDescription>
                                Add a new application process step for{' '}
                                {
                                    addStepDialog.data?.representingCountry
                                        .country.name
                                }
                            </DialogDescription>
                        </DialogHeader>
                        <form onSubmit={handleSubmitAddStep} className="space-y-4">
                            <div className="space-y-2">
                                <Label htmlFor="status-name">
                                    Step Name
                                </Label>
                                <Input
                                    id="status-name"
                                    value={addStepFormData.status_name}
                                    onChange={(e) =>
                                        setAddStepData('status_name', e.target.value)
                                    }
                                    placeholder="Enter step name (e.g., Document Submission)"
                                    required
                                />
                                {addStepErrors.status_name && (
                                    <p className="text-sm text-red-600">
                                        {addStepErrors.status_name}
                                    </p>
                                )}
                            </div>

                            <DialogFooter>
                                <Button
                                    type="button"
                                    variant="outline"
                                    onClick={() => {
                                        const dialog = document.querySelector('[role="dialog"]');
                                        if (dialog) {
                                            dialog.setAttribute('data-state', 'closed');
                                        }
                                        setTimeout(() => {
                                            addStepDialog.close();
                                            resetAddStep();
                                        }, 200);
                                    }}
                                >
                                    Cancel
                                </Button>
                                <Button type="submit" disabled={addingStep}>
                                    {addingStep ? 'Adding...' : 'Add Step'}
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>

                {/* Delete Status Alert Dialog */}
                <AlertDialog
                    open={deleteStatusAlert.isOpen}
                    onOpenChange={(open) => !open && deleteStatusAlert.close()}
                >
                    <AlertDialogContent>
                        <AlertDialogHeader>
                            <AlertDialogTitle>Are you sure?</AlertDialogTitle>
                            <AlertDialogDescription>
                                Are you sure you want to delete "
                                {deleteStatusAlert.data?.status.custom_name ||
                                    deleteStatusAlert.data?.status.status_name}
                                "? This will also delete all its sub-statuses. This action
                                cannot be easily undone.
                            </AlertDialogDescription>
                        </AlertDialogHeader>
                        <AlertDialogFooter>
                            <AlertDialogCancel>Cancel</AlertDialogCancel>
                            <AlertDialogAction
                                onClick={confirmDeleteStatus}
                                className="bg-red-600 hover:bg-red-700"
                            >
                                Delete
                            </AlertDialogAction>
                        </AlertDialogFooter>
                    </AlertDialogContent>
                </AlertDialog>

                {/* Delete Sub-Status Alert Dialog */}
                <AlertDialog
                    open={deleteSubStatusAlert.isOpen}
                    onOpenChange={(open) => !open && deleteSubStatusAlert.close()}
                >
                    <AlertDialogContent>
                        <AlertDialogHeader>
                            <AlertDialogTitle>Are you sure?</AlertDialogTitle>
                            <AlertDialogDescription>
                                Are you sure you want to delete sub-status "
                                {deleteSubStatusAlert.data?.subStatus.name}"? This action
                                cannot be easily undone.
                            </AlertDialogDescription>
                        </AlertDialogHeader>
                        <AlertDialogFooter>
                            <AlertDialogCancel>Cancel</AlertDialogCancel>
                            <AlertDialogAction
                                onClick={confirmDeleteSubStatus}
                                className="bg-red-600 hover:bg-red-700"
                            >
                                Delete
                            </AlertDialogAction>
                        </AlertDialogFooter>
                    </AlertDialogContent>
                </AlertDialog>

                {/* Delete Representing Country Alert Dialog */}
                <AlertDialog
                    open={deleteRepCountryAlert.isOpen}
                    onOpenChange={(open) => !open && deleteRepCountryAlert.close()}
                >
                    <AlertDialogContent>
                        <AlertDialogHeader>
                            <AlertDialogTitle>Delete Representing Country?</AlertDialogTitle>
                            <AlertDialogDescription>
                                Are you sure you want to remove{' '}
                                <span className="font-semibold">
                                    {deleteRepCountryAlert.data?.countryName}
                                </span>{' '}
                                from representing countries? This will also delete all associated
                                statuses and sub-statuses. This action cannot be easily undone.
                            </AlertDialogDescription>
                        </AlertDialogHeader>
                        <AlertDialogFooter>
                            <AlertDialogCancel>Cancel</AlertDialogCancel>
                            <AlertDialogAction
                                onClick={confirmDeleteRepCountry}
                                className="bg-red-600 hover:bg-red-700"
                            >
                                Delete Country
                            </AlertDialogAction>
                        </AlertDialogFooter>
                    </AlertDialogContent>
                </AlertDialog>

                {/* Edit Status Dialog */}
                <Dialog
                    open={statusDialog.isOpen}
                    onOpenChange={(open) => {
                        if (!open) {
                            setTimeout(() => {
                                statusDialog.close();
                                reset();
                            }, 200);
                        }
                    }}
                >
                    <DialogContent className="sm:max-w-[425px]">
                        <DialogHeader>
                            <DialogTitle>Edit Status Name</DialogTitle>
                            <DialogDescription>
                                Customize the status name for{' '}
                                {
                                    statusDialog.data?.representingCountry
                                        .country.name
                                }
                            </DialogDescription>
                        </DialogHeader>
                        <form onSubmit={handleSubmitStatus} className="space-y-4">
                            <div className="space-y-2">
                                <Label htmlFor="custom-name">
                                    Custom Status Name
                                    {statusDialog.data?.status && (
                                        <span className="ml-1 text-xs text-muted-foreground">
                                            (Default:{' '}
                                            {
                                                statusDialog.data.status
                                                    .status_name
                                            }
                                            )
                                        </span>
                                    )}
                                </Label>
                                <Input
                                    id="custom-name"
                                    value={formData.custom_name}
                                    onChange={(e) =>
                                        setData('custom_name', e.target.value)
                                    }
                                    placeholder="Enter custom status name"
                                    required
                                />
                                {errors.custom_name && (
                                    <p className="text-sm text-red-600">
                                        {errors.custom_name}
                                    </p>
                                )}
                            </div>

                            <DialogFooter>
                                <Button
                                    type="button"
                                    variant="outline"
                                    onClick={() => {
                                        const dialog = document.querySelector('[role="dialog"]');
                                        if (dialog) {
                                            dialog.setAttribute('data-state', 'closed');
                                        }
                                        setTimeout(() => {
                                            statusDialog.close();
                                            reset();
                                        }, 200);
                                    }}
                                >
                                    Cancel
                                </Button>
                                <Button type="submit" disabled={processing}>
                                    {processing ? 'Updating...' : 'Update Status'}
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>
        </AppLayout>
    );
}
