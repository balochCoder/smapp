import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
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
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { 
    PlusIcon, 
    Edit, 
    Trash2, 
    Eye, 
    FileText, 
    Calendar,
    ArrowUpDown,
    ChevronDown,
    Pencil,
    Copy,
    MoreHorizontal
} from 'lucide-react';
import { dashboard } from '@/routes';
import * as representingCountries from '@/routes/representing-countries';
import * as applicationProcesses from '@/routes/application-processes';
import { useState } from 'react';

interface Country {
    id: string;
    name: string;
    flag: string;
}

interface ApplicationProcess {
    id: string;
    name: string;
}

interface RepresentingCountry {
    id: string;
    monthly_living_cost: string | null;
    is_active: boolean;
    created_at: string;
    country: Country;
    application_processes: ApplicationProcess[];
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
    const [expandedCountries, setExpandedCountries] = useState<Set<string>>(new Set());
    const [addStepDialogOpen, setAddStepDialogOpen] = useState(false);
    const [selectedRepresentingCountry, setSelectedRepresentingCountry] = useState<RepresentingCountry | null>(null);

    const { data: formData, setData, post, processing, errors, reset } = useForm({
        name: '',
        representing_country_ids: [] as string[],
    });

    const handleDelete = (id: string, countryName: string) => {
        if (
            confirm(
                `Are you sure you want to remove ${countryName} from representing countries?`
            )
        ) {
            router.delete(representingCountries.destroy(id).url);
        }
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

    const handleAddStep = (repCountry: RepresentingCountry) => {
        setSelectedRepresentingCountry(repCountry);
        setData({
            name: '',
            representing_country_ids: [repCountry.id],
        });
        setAddStepDialogOpen(true);
    };

    const handleSubmitAddStep = (e: React.FormEvent) => {
        e.preventDefault();
        post(applicationProcesses.store().url, {
            onSuccess: () => {
                setAddStepDialogOpen(false);
                setSelectedRepresentingCountry(null);
                reset();
                // Reload the current page to refresh the representing countries data
                router.reload({ only: ['representingCountries'] });
            },
        });
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-GB', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
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
                        const isExpanded = expandedCountries.has(repCountry.id);
                        const visibleProcesses = isExpanded 
                            ? repCountry.application_processes 
                            : repCountry.application_processes.slice(0, 3);
                        const remainingCount = repCountry.application_processes.length - 3;
                        
                        return (
                            <Card key={repCountry.id} className="overflow-hidden">
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
                                                    checked={repCountry.is_active}
                                                    disabled
                                                    className="h-3 w-6 data-[state=checked]:bg-blue-600"
                                                />
                                                <span className={`text-xs font-medium ${
                                                    repCountry.is_active 
                                                        ? 'text-blue-600' 
                                                        : 'text-gray-500'
                                                }`}>
                                                    {repCountry.is_active ? 'Active' : 'Inactive'}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </CardHeader>
                                
                                <CardContent className="px-4 pb-4 space-y-3">
                                    {/* Application Summary */}
                                    <div className="space-y-1">
                                        <div className="flex items-center gap-1.5 text-xs text-gray-600">
                                            <FileText className="h-3 w-3" />
                                            <span>{repCountry.application_processes.length} steps</span>
                                        </div>
                                        <div className="flex items-center gap-1.5 text-xs text-gray-600">
                                            <Calendar className="h-3 w-3" />
                                            <span>Added: {formatDate(repCountry.created_at)}</span>
                                        </div>
                                    </div>

                                    {/* Action Buttons */}
                                    <div className="flex gap-1">
                                        <Link 
                                            href={representingCountries.notes(repCountry.id).url}
                                            className="flex-1"
                                        >
                                            <Button size="sm" variant="outline" className="w-full h-7 text-xs px-2">
                                                <FileText className="mr-1 h-3 w-3" />
                                                Notes
                                            </Button>
                                        </Link>
                                        <Button size="sm" variant="outline" className="flex-1 h-7 text-xs px-2">
                                            <ArrowUpDown className="mr-1 h-3 w-3" />
                                            Reorder
                                        </Button>
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

                                    {/* Application Steps Section */}
                                    <div className="border-t pt-3">
                                        <div className="flex items-center justify-between mb-2">
                                            <span className="text-xs font-medium">Steps:</span>
                                            {repCountry.application_processes.length > 3 && (
                                                <Button
                                                    variant="ghost"
                                                    size="sm"
                                                    onClick={() => toggleCountryExpansion(repCountry.id)}
                                                    className="h-5 px-1.5 text-xs"
                                                >
                                                    View All ({repCountry.application_processes.length})
                                                    <ChevronDown className={`ml-1 h-2.5 w-2.5 transition-transform ${
                                                        isExpanded ? 'rotate-180' : ''
                                                    }`} />
                                                </Button>
                                            )}
                                        </div>

                                        <div className="space-y-1.5">
                                            {visibleProcesses.map((process) => (
                                                <div key={process.id} className="flex items-center justify-between p-1.5 bg-gray-50 rounded">
                                                    <span className="text-xs font-medium truncate flex-1 mr-2">{process.name}</span>
                                                    <div className="flex items-center gap-1">
                                                        <Switch 
                                                            defaultChecked={true}
                                                            disabled
                                                            className="h-3 w-5 data-[state=checked]:bg-blue-600"
                                                        />
                                                        <div className="flex items-center gap-1 ml-2">
                                                            <Button variant="ghost" size="sm" className="h-5 w-5 p-0">
                                                                <Pencil className="h-2.5 w-2.5" />
                                                            </Button>
                                                            <Button variant="ghost" size="sm" className="h-5 w-5 p-0">
                                                                <Copy className="h-2.5 w-2.5" />
                                                            </Button>
                                                            <Button variant="ghost" size="sm" className="h-5 w-5 p-0 text-red-600 hover:text-red-700">
                                                                <Trash2 className="h-2.5 w-2.5" />
                                                            </Button>
                                                        </div>
                                                    </div>
                                                </div>
                                            ))}
                                            
                                            {!isExpanded && remainingCount > 0 && (
                                                <div className="text-center py-1">
                                                    <span className="text-xs text-gray-500">+{remainingCount} more</span>
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

                {/* Add Step Dialog */}
                <Dialog open={addStepDialogOpen} onOpenChange={setAddStepDialogOpen}>
                    <DialogContent className="sm:max-w-[425px]">
                        <DialogHeader>
                            <DialogTitle>
                                Add Application Process Step for {selectedRepresentingCountry?.country.name}
                            </DialogTitle>
                        </DialogHeader>
                        <form onSubmit={handleSubmitAddStep} className="space-y-4">
                            <div className="space-y-2">
                                <Label htmlFor="name">Process Name</Label>
                                <Input
                                    id="name"
                                    value={formData.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    placeholder="Enter process name"
                                    required
                                />
                                {errors.name && (
                                    <p className="text-sm text-red-600">{errors.name}</p>
                                )}
                            </div>

                            <DialogFooter>
                                <Button
                                    type="button"
                                    variant="outline"
                                    onClick={() => setAddStepDialogOpen(false)}
                                >
                                    Cancel
                                </Button>
                                <Button type="submit" disabled={processing}>
                                    {processing ? 'Creating...' : 'Create Step'}
                                </Button>
                            </DialogFooter>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>
        </AppLayout>
    );
}
