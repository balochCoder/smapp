import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import {
    Pagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationLink,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
    CommandList,
} from '@/components/ui/command';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import { cn } from '@/lib/utils';
import {
    type BreadcrumbItem,
    type Country,
    type SubStatus,
    type RepCountryStatus,
    type RepresentingCountry,
    type PaginatedData,
} from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import {
    Calendar,
    ChevronDown,
    List,
    Eye,
    X,
    Filter,
    Check,
    ChevronsUpDown,
} from 'lucide-react';
import * as representingCountries from '@/routes/branch/representing-countries';
import { useState } from 'react';

interface Props {
    representingCountries: PaginatedData<RepresentingCountry>;
    availableCountries?: Country[];
    filters?: {
        country?: string;
        status?: string;
    };
    statistics: {
        totalCountries: number;
        activeCountries: number;
    };
    permissions: {
        canCreate: boolean;
        canEdit: boolean;
        canDelete: boolean;
        canManageStatus: boolean;
    };
}

interface SubStatusSheetData {
    representingCountry: RepresentingCountry;
    status: RepCountryStatus;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Branch Dashboard',
        href: '/branch/dashboard',
    },
    {
        title: 'Representing Countries',
        href: representingCountries.index().url,
    },
];

export default function BranchIndex({ representingCountries: data, availableCountries, filters, statistics }: Props) {
    const [expandedCountries, setExpandedCountries] = useState<Set<string>>(
        new Set()
    );
    const [isSheetClosing, setIsSheetClosing] = useState(false);
    const [countryPopoverOpen, setCountryPopoverOpen] = useState(false);
    const [subStatusSheet, setSubStatusSheet] = useState<{
        isOpen: boolean;
        data: SubStatusSheetData | null;
    }>({
        isOpen: false,
        data: null,
    });

    // Get filters from URL
    const statusFilter = (filters?.status as 'all' | 'active' | 'inactive') || 'all';
    const countryFilter = filters?.country || 'all';

    const handleViewSubStatuses = (
        repCountry: RepresentingCountry,
        status: RepCountryStatus
    ) => {
        setSubStatusSheet({
            isOpen: true,
            data: { representingCountry: repCountry, status },
        });
    };

    const closeSubStatusSheet = () => {
        setIsSheetClosing(true);
        setTimeout(() => {
            setSubStatusSheet({ isOpen: false, data: null });
            setIsSheetClosing(false);
        }, 300);
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

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-GB', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
        });
    };

    const generatePageNumbers = () => {
        const pages: (number | string)[] = [];
        const currentPage = data.meta.current_page;
        const lastPage = data.meta.last_page;
        const delta = 2;

        if (lastPage <= 7) {
            for (let i = 1; i <= lastPage; i++) {
                pages.push(i);
            }
        } else {
            pages.push(1);
            const rangeStart = Math.max(2, currentPage - delta);
            const rangeEnd = Math.min(lastPage - 1, currentPage + delta);

            if (rangeStart > 2) {
                pages.push('ellipsis-start');
            }

            for (let i = rangeStart; i <= rangeEnd; i++) {
                pages.push(i);
            }

            if (rangeEnd < lastPage - 1) {
                pages.push('ellipsis-end');
            }

            pages.push(lastPage);
        }

        return pages;
    };

    const goToPage = (page: number) => {
        router.get(
            representingCountries.index().url,
            {
                page,
                country: countryFilter !== 'all' ? countryFilter : undefined,
                status: statusFilter !== 'all' ? statusFilter : undefined,
            },
            { preserveScroll: true, preserveState: true }
        );
    };

    const handleClearFilters = () => {
        router.get(
            representingCountries.index().url,
            {},
            { preserveScroll: true, preserveState: true }
        );
    };

    const handleCountrySelect = (countryId: string) => {
        setCountryPopoverOpen(false);
        router.get(
            representingCountries.index().url,
            {
                country: countryId === 'all' ? undefined : countryId,
                status: statusFilter !== 'all' ? statusFilter : undefined,
            },
            { preserveScroll: true, preserveState: true }
        );
    };

    const handleStatusSelect = (status: 'all' | 'active' | 'inactive') => {
        router.get(
            representingCountries.index().url,
            {
                country: countryFilter !== 'all' ? countryFilter : undefined,
                status: status !== 'all' ? status : undefined,
            },
            { preserveScroll: true, preserveState: true }
        );
    };

    const getSelectedCountryName = () => {
        if (countryFilter === 'all') return 'All Countries';
        const country = availableCountries?.find((c) => c.id === countryFilter);
        return country ? country.name : 'All Countries';
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Representing Countries - Branch View" />

            <div className="flex h-full flex-1 flex-col gap-4 p-4">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight">
                            Representing Countries
                        </h1>
                        <p className="text-muted-foreground">
                            View countries your organization represents (Read-only access)
                        </p>
                    </div>
                </div>

                {/* Filter Section */}
                <Card>
                    <CardContent className="p-4">
                        <div className="flex flex-col gap-3">
                            <div className="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                                {/* Country Filter Dropdown */}
                                {availableCountries && availableCountries.length > 0 && (
                                    <div className="space-y-2 sm:w-[280px]">
                                        <Label htmlFor="country-filter" className="text-sm font-medium">
                                            Filter by Country
                                        </Label>
                                        <Popover open={countryPopoverOpen} onOpenChange={setCountryPopoverOpen}>
                                            <PopoverTrigger asChild>
                                                <Button
                                                    id="country-filter"
                                                    variant="outline"
                                                    role="combobox"
                                                    aria-expanded={countryPopoverOpen}
                                                    className="w-full justify-between"
                                                >
                                                    <div className="flex min-w-0 items-center gap-2">
                                                        {countryFilter !== 'all' && (
                                                            <img
                                                                src={availableCountries.find((c) => c.id === countryFilter)?.flag}
                                                                alt=""
                                                                className="h-3 w-4 flex-shrink-0 rounded"
                                                            />
                                                        )}
                                                        <span className="truncate">{getSelectedCountryName()}</span>
                                                    </div>
                                                    <ChevronsUpDown className="ml-2 h-4 w-4 shrink-0 opacity-50" />
                                                </Button>
                                            </PopoverTrigger>
                                            <PopoverContent className="w-full p-0 sm:w-[280px]">
                                                <Command>
                                                    <CommandInput placeholder="Search countries..." />
                                                    <CommandList>
                                                        <CommandEmpty>No country found.</CommandEmpty>
                                                        <CommandGroup>
                                                            <CommandItem
                                                                value="all"
                                                                onSelect={() => handleCountrySelect('all')}
                                                            >
                                                                <Check
                                                                    className={cn(
                                                                        'mr-2 h-4 w-4',
                                                                        countryFilter === 'all' ? 'opacity-100' : 'opacity-0'
                                                                    )}
                                                                />
                                                                All Countries
                                                            </CommandItem>
                                                            {availableCountries.map((country) => (
                                                                <CommandItem
                                                                    key={country.id}
                                                                    value={country.name}
                                                                    onSelect={() => handleCountrySelect(country.id)}
                                                                >
                                                                    <Check
                                                                        className={cn(
                                                                            'mr-2 h-4 w-4',
                                                                            countryFilter === country.id ? 'opacity-100' : 'opacity-0'
                                                                        )}
                                                                    />
                                                                    <div className="flex min-w-0 items-center gap-2">
                                                                        <img
                                                                            src={country.flag}
                                                                            alt={country.name}
                                                                            className="h-3 w-4 flex-shrink-0 rounded"
                                                                        />
                                                                        <span className="truncate">{country.name}</span>
                                                                    </div>
                                                                </CommandItem>
                                                            ))}
                                                        </CommandGroup>
                                                    </CommandList>
                                                </Command>
                                            </PopoverContent>
                                        </Popover>
                                    </div>
                                )}

                                {/* Status Filter and Clear Button */}
                                <div className="flex flex-col gap-3 sm:flex-row sm:items-center">
                                    <div className="flex items-center gap-2">
                                        <Filter className="h-4 w-4 text-muted-foreground" />
                                        <span className="text-sm font-medium">Status:</span>
                                        <div className="flex gap-2">
                                            <Button
                                                variant={statusFilter === 'all' ? 'default' : 'outline'}
                                                size="sm"
                                                onClick={() => handleStatusSelect('all')}
                                                disabled={statusFilter === 'all'}
                                            >
                                                All
                                            </Button>
                                            <Button
                                                variant={statusFilter === 'active' ? 'default' : 'outline'}
                                                size="sm"
                                                onClick={() => handleStatusSelect('active')}
                                            >
                                                Active
                                            </Button>
                                        </div>
                                    </div>

                                    {/* Clear Filters Button */}
                                    {(statusFilter !== 'all' || countryFilter !== 'all') && (
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            onClick={handleClearFilters}
                                            className="w-full sm:w-auto "
                                        >
                                            <X className="mr-2 h-4 w-4" />
                                            Clear Filters
                                        </Button>
                                    )}
                                </div>
                            </div>

                            {/* Filter Summary */}
                            {(statusFilter !== 'all' || countryFilter !== 'all') && (
                                <div className="flex flex-wrap items-center gap-2 text-sm text-muted-foreground border-t pt-3">
                                    <span>
                                        {data?.meta?.total || 0} {(data?.meta?.total || 0) === 1 ? 'country' : 'countries'} found
                                    </span>
                                    {statusFilter !== 'all' && (
                                        <Badge variant="secondary" className="text-xs">
                                            Status: {statusFilter}
                                        </Badge>
                                    )}
                                    {countryFilter !== 'all' && (
                                        <Badge variant="secondary" className="text-xs">
                                            Country: {getSelectedCountryName()}
                                        </Badge>
                                    )}
                                </div>
                            )}
                        </div>
                    </CardContent>
                </Card>

                {/* Statistics Section */}
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <Card>
                        <CardContent className="p-4">
                            <div className="flex items-center gap-3">
                                <div className="flex h-10 w-10 items-center justify-center rounded-full bg-info/10 dark:bg-info/30">
                                    <List className="h-5 w-5 text-info dark:text-info-foreground" />
                                </div>
                                <div className="min-w-0 flex-1">
                                    <p className="text-sm text-muted-foreground">Total Countries</p>
                                    <p className="text-2xl font-bold">
                                        {statistics.totalCountries}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                    <Card>
                        <CardContent className="p-4">
                            <div className="flex items-center gap-3">
                                <div className="flex h-10 w-10 items-center justify-center rounded-full bg-success/10 dark:bg-success/30">
                                    <Check className="h-5 w-5 text-success dark:text-success-foreground" />
                                </div>
                                <div className="min-w-0 flex-1">
                                    <p className="text-sm text-muted-foreground">Active Countries</p>
                                    <p className="text-2xl font-bold">
                                        {statistics.activeCountries}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                {/* Countries Grid */}
                <div className="grid gap-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    {data?.data?.map((repCountry) => {
                        if (!repCountry.id) return null;

                        const isExpanded = expandedCountries.has(repCountry.id);
                        const statuses = repCountry.rep_country_statuses || [];
                        const visibleStatuses = isExpanded
                            ? statuses
                            : statuses.slice(0, 3);
                        const remainingCount = statuses.length - 3;

                        return (
                            <Card
                                key={repCountry.id}
                                className="overflow-hidden"
                            >
                                <CardHeader className="pb-3 px-4 pt-4">
                                    <div className="flex items-start gap-2">
                                        {repCountry.country?.flag && (
                                            <img
                                                src={repCountry.country.flag}
                                                alt={repCountry.country.name}
                                                className="h-6 w-8 rounded object-cover border"
                                            />
                                        )}
                                        <div className="flex-1 min-w-0">
                                            <CardTitle className="text-sm font-semibold truncate">
                                                {repCountry.country?.name}
                                            </CardTitle>
                                            <div className="flex items-center gap-1.5 mt-1">
                                                <Badge variant={repCountry.is_active ? 'default' : 'secondary'} className="text-xs">
                                                    {repCountry.is_active ? 'Active' : 'Inactive'}
                                                </Badge>
                                            </div>
                                        </div>
                                    </div>
                                </CardHeader>

                                <CardContent className="px-4 pb-4 space-y-3">
                                    <div className="space-y-1">
                                        <div className="flex items-center gap-1.5 text-xs text-muted-foreground">
                                            <List className="h-3 w-3" />
                                            <span>
                                                {statuses.length}{' '}
                                                {statuses.length === 1 ? 'process step' : 'process steps'}
                                            </span>
                                        </div>
                                        <div className="flex items-center gap-1.5 text-xs text-muted-foreground">
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
                                        <Link
                                            href={representingCountries.show(
                                                repCountry.id
                                            )}
                                            className="w-full"
                                        >
                                            <Button
                                                size="sm"
                                                variant="outline"
                                                className="w-full h-7 text-xs px-2"
                                            >
                                                <Eye className="mr-1 h-3 w-3" />
                                                View Details
                                            </Button>
                                        </Link>
                                    </div>

                                    {/* Statuses List */}
                                    <div className="border-t pt-3">
                                        <div className="flex items-center justify-between mb-2">
                                            <span className="text-xs font-medium">
                                                Statuses:
                                            </span>
                                            {statuses.length > 3 && (
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
                                                    View All ({statuses.length})
                                                    <ChevronDown
                                                        className={`ml-1 h-2.5 w-2.5 transition-transform ${isExpanded
                                                            ? 'rotate-180'
                                                            : ''
                                                            }`}
                                                    />
                                                </Button>
                                            )}
                                        </div>

                                        <div className="space-y-1.5">
                                            {visibleStatuses.map(
                                                (status, index) => (
                                                    <div
                                                        key={status.id}
                                                        className="flex items-center gap-2 p-1.5 bg-muted/50 dark:bg-muted rounded"
                                                    >
                                                        <div className="flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-primary/10 text-xs font-semibold text-primary">
                                                            {index + 1}
                                                        </div>
                                                        <span className="text-xs font-medium truncate flex-1 mr-2">
                                                            {status.custom_name ||
                                                                status.status_name}
                                                        </span>
                                                        <div className="flex items-center gap-1">
                                                            <Badge variant={status.is_active ? 'default' : 'secondary'} className="text-xs">
                                                                {status.is_active ? 'Active' : 'Inactive'}
                                                            </Badge>
                                                            {status.sub_statuses && status.sub_statuses.length > 0 && (
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
                                                            )}
                                                        </div>
                                                    </div>
                                                )
                                            )}

                                            {!isExpanded && remainingCount > 0 && (
                                                <div className="text-center py-1">
                                                    <span className="text-xs text-muted-foreground">
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

                {/* Pagination */}
                {data?.data && data.data.length > 0 && data.meta.last_page > 1 && (
                    <Pagination>
                        <PaginationContent>
                            <PaginationItem>
                                <PaginationPrevious
                                    href="#"
                                    onClick={(e) => {
                                        e.preventDefault();
                                        if (data.meta.current_page > 1) {
                                            goToPage(data.meta.current_page - 1);
                                        }
                                    }}
                                    className={
                                        data.meta.current_page === 1
                                            ? 'pointer-events-none opacity-50'
                                            : ''
                                    }
                                />
                            </PaginationItem>

                            {generatePageNumbers().map((page, index) => {
                                if (typeof page === 'string') {
                                    return (
                                        <PaginationItem key={`${page}-${index}`}>
                                            <PaginationEllipsis />
                                        </PaginationItem>
                                    );
                                }

                                return (
                                    <PaginationItem key={page}>
                                        <PaginationLink
                                            href="#"
                                            onClick={(e) => {
                                                e.preventDefault();
                                                goToPage(page);
                                            }}
                                            isActive={data.meta.current_page === page}
                                        >
                                            {page}
                                        </PaginationLink>
                                    </PaginationItem>
                                );
                            })}

                            <PaginationItem>
                                <PaginationNext
                                    href="#"
                                    onClick={(e) => {
                                        e.preventDefault();
                                        if (data.meta.current_page < data.meta.last_page) {
                                            goToPage(data.meta.current_page + 1);
                                        }
                                    }}
                                    className={
                                        data.meta.current_page === data.meta.last_page
                                            ? 'pointer-events-none opacity-50'
                                            : ''
                                    }
                                />
                            </PaginationItem>
                        </PaginationContent>
                    </Pagination>
                )}

                {/* Empty State */}
                {(!data?.data || data.data.length === 0) && (
                    <Card>
                        <CardContent className="flex flex-col items-center justify-center py-12">
                            {(statusFilter !== 'all' || countryFilter !== 'all') ? (
                                <>
                                    <p className="text-muted-foreground mb-4">
                                        No countries match your filters
                                    </p>
                                    <Button
                                        variant="outline"
                                        onClick={handleClearFilters}
                                    >
                                        <X className="mr-2 h-4 w-4" />
                                        Clear Filters
                                    </Button>
                                </>
                            ) : (
                                <p className="text-muted-foreground">
                                    No representing countries available
                                </p>
                            )}
                        </CardContent>
                    </Card>
                )}

                {/* Sub-Status Sheet (View-Only) */}
                <Sheet
                    open={subStatusSheet.isOpen && !isSheetClosing}
                    onOpenChange={(open) => {
                        if (!open) {
                            closeSubStatusSheet();
                        }
                    }}
                >
                    <SheetContent className="flex h-full flex-col gap-0 p-0 sm:max-w-xl">
                        {(subStatusSheet.isOpen || isSheetClosing) && subStatusSheet.data && (
                            <>
                                <SheetHeader className="shrink-0 space-y-3 border-b px-6 pb-4 pt-6">
                                    <SheetTitle className="text-xl">
                                        Sub-Steps for "{subStatusSheet.data.status.custom_name ||
                                            subStatusSheet.data.status.status_name}"
                                    </SheetTitle>
                                    <SheetDescription className="text-sm">
                                        View sub-steps for{' '}
                                        {subStatusSheet.data.representingCountry.country?.name}
                                    </SheetDescription>
                                </SheetHeader>
                                <div className="flex-1 space-y-4 overflow-y-auto px-4 py-6">
                                    {subStatusSheet.data.status.sub_statuses &&
                                        subStatusSheet.data.status.sub_statuses.length > 0 ? (
                                        <div className="space-y-3">
                                            {subStatusSheet.data.status.sub_statuses.map(
                                                (subStatus, index) => (
                                                    <div
                                                        key={subStatus.id}
                                                        className="flex items-start gap-3 rounded-lg border bg-card p-4"
                                                    >
                                                        <div className="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-primary/10 text-sm font-semibold text-primary">
                                                            {index + 1}
                                                        </div>
                                                        <div className="min-w-0 flex-1">
                                                            <p className="text-sm font-medium">
                                                                {subStatus.name}
                                                            </p>
                                                            {subStatus.description && (
                                                                <p className="mt-1 text-xs text-muted-foreground">
                                                                    {subStatus.description}
                                                                </p>
                                                            )}
                                                        </div>
                                                        <Badge variant={subStatus.is_active ? 'default' : 'secondary'} className="text-xs">
                                                            {subStatus.is_active ? 'Active' : 'Inactive'}
                                                        </Badge>
                                                    </div>
                                                )
                                            )}
                                        </div>
                                    ) : (
                                        <div className="flex flex-col items-center justify-center rounded-lg border border-dashed py-12 text-center">
                                            <div className="bg-muted mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full">
                                                <List className="text-muted-foreground h-8 w-8" />
                                            </div>
                                            <p className="text-muted-foreground mb-2 text-sm font-medium">
                                                No sub-steps added yet
                                            </p>
                                        </div>
                                    )}
                                </div>
                            </>
                        )}
                    </SheetContent>
                </Sheet>
            </div>
        </AppLayout>
    );
}
