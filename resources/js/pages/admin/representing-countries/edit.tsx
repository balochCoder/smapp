import Heading from '@/components/heading';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { MultiSelect } from '@/components/ui/multi-select';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/app-layout';
import {
    type BreadcrumbItem,
    type RepresentingCountry,
    type ApplicationProcess,
} from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { ArrowLeft, Briefcase, FileText, Gift, Globe, Settings } from 'lucide-react';
import { dashboard } from '@/routes';
import * as representingCountries from '@/routes/admin/representing-countries';

interface Props {
    representingCountry: RepresentingCountry;
    applicationProcesses: ApplicationProcess[];
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

type FormField =
    | 'monthly_living_cost'
    | 'visa_requirements'
    | 'part_time_work_details'
    | 'country_benefits'
    | 'currency'
    | 'application_process_ids';

export default function Edit({
    representingCountry,
    applicationProcesses,
}: Props) {
    const newProcess = applicationProcesses.find((p) => p.name === 'New');
    const newProcessId = newProcess ? newProcess.id : '';

    const { data, setData, put, processing, errors } = useForm({
        monthly_living_cost: representingCountry.monthly_living_cost || '',
        visa_requirements: representingCountry.visa_requirements || '',
        part_time_work_details: representingCountry.part_time_work_details || '',
        country_benefits: representingCountry.country_benefits || '',
        currency: representingCountry.currency || 'USD',
        application_process_ids: representingCountry.application_processes?.map((p) => p.id) || (newProcessId ? [newProcessId] : []),
    });

    const handleInputChange = (field: FormField) => (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        setData(field, e.target.value);
    };

    const handleProcessChange = (selected: string[]) => {
        // MultiSelect component now automatically preserves disabled options
        setData('application_process_ids', selected);
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        put(representingCountries.update(representingCountry.id).url, {
            onSuccess: () => router.visit(representingCountries.show(representingCountry.id).url),
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Edit ${representingCountry.country.name}`} />

            <div className="flex h-full min-w-0 flex-1 flex-col space-y-4 p-4 sm:space-y-6 sm:p-6">
                {/* Header Section */}
                <div className="flex min-w-0 flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div className="flex min-w-0 flex-1 items-center gap-4">
                        {representingCountry.country.flag && (
                            <img
                                src={representingCountry.country.flag}
                                alt={representingCountry.country.name}
                                className="h-12 w-16 flex-shrink-0 rounded object-cover shadow-sm"
                            />
                        )}
                        <div className="min-w-0 flex-1">
                            <Heading
                                title={`Edit ${representingCountry.country.name}`}
                                description="Update representing country information"
                            />
                        </div>
                    </div>
                    <Link href={representingCountries.index()} className="w-full sm:w-auto">
                        <Button variant="default" className="w-full cursor-pointer">
                            <ArrowLeft className="mr-2 h-4 w-4" />
                            Back to Countries
                        </Button>
                    </Link>
                </div>

                <form onSubmit={handleSubmit} className="min-w-0 space-y-4 sm:space-y-6">
                    {/* Basic Information Card */}
                    <Card>
                        <CardHeader>
                            <div className="flex min-w-0 flex-col space-y-3 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-2">
                                <div className="w-fit flex-shrink-0 rounded-lg bg-info/10 dark:bg-info/30 p-2">
                                    <Globe className="h-4 w-4 text-info dark:text-info-foreground" />
                                </div>
                                <div className="min-w-0 flex-1">
                                    <CardTitle className="text-lg sm:text-xl">
                                        Basic Information
                                    </CardTitle>
                                    <p className="mt-1 text-xs text-muted-foreground sm:text-sm">
                                        Update country basic details
                                    </p>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent className="space-y-4 sm:space-y-6">
                            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-6">
                                <div className="space-y-2">
                                    <Label htmlFor="monthly_living_cost" className="text-sm font-medium">
                                        Monthly Living Cost ({data.currency})
                                    </Label>
                                    <Input
                                        type="number"
                                        id="monthly_living_cost"
                                        value={data.monthly_living_cost}
                                        onChange={handleInputChange('monthly_living_cost')}
                                        placeholder="e.g., 1200.00"
                                        step="0.01"
                                    />
                                    {errors.monthly_living_cost && <p className="text-sm text-destructive">{errors.monthly_living_cost}</p>}
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    {/* Requirements Card */}
                    <Card>
                        <CardHeader>
                            <div className="flex min-w-0 flex-col space-y-3 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-2">
                                <div className="w-fit flex-shrink-0 rounded-lg bg-warning/10 dark:bg-warning/30 p-2">
                                    <FileText className="h-4 w-4 text-warning dark:text-warning-foreground" />
                                </div>
                                <div className="min-w-0 flex-1">
                                    <CardTitle className="text-lg sm:text-xl">
                                        Requirements & Procedures
                                    </CardTitle>
                                    <p className="mt-1 text-xs text-muted-foreground sm:text-sm">
                                        Document visa requirements and application procedures
                                    </p>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-2">
                                <Label htmlFor="visa_requirements" className="text-sm font-medium">
                                    Visa Requirements
                                </Label>
                                <Textarea
                                    id="visa_requirements"
                                    value={data.visa_requirements}
                                    onChange={handleInputChange('visa_requirements')}
                                    placeholder="Enter detailed visa requirements, application procedures, required documents, processing times, and any special conditions..."
                                    rows={4}
                                    className="resize-none"
                                />
                                {errors.visa_requirements && <p className="text-sm text-destructive">{errors.visa_requirements}</p>}
                            </div>
                        </CardContent>
                    </Card>

                    {/* Work & Benefits Card */}
                    <div className="grid grid-cols-1 gap-4 sm:gap-6">
                        <Card>
                            <CardHeader>
                                <div className="flex min-w-0 flex-col space-y-3 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-2">
                                    <div className="w-fit flex-shrink-0 rounded-lg bg-success/10 dark:bg-success/30 p-2">
                                        <Briefcase className="h-4 w-4 text-success dark:text-success-foreground" />
                                    </div>
                                    <div className="min-w-0 flex-1">
                                        <CardTitle className="text-lg sm:text-xl">
                                            Work Opportunities
                                        </CardTitle>
                                        <p className="mt-1 text-xs text-muted-foreground sm:text-sm">
                                            Part-time work details and restrictions
                                        </p>
                                    </div>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-2">
                                    <Label htmlFor="part_time_work_details" className="text-sm font-medium">
                                        Part-time Work Details
                                    </Label>
                                    <Textarea
                                        id="part_time_work_details"
                                        value={data.part_time_work_details}
                                        onChange={handleInputChange('part_time_work_details')}
                                        placeholder="Enter part-time work opportunities, restrictions, hours allowed, types of jobs available, and any special conditions..."
                                        rows={4}
                                        className="resize-none"
                                    />
                                    {errors.part_time_work_details && <p className="text-sm text-destructive">{errors.part_time_work_details}</p>}
                                </div>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader>
                                <div className="flex min-w-0 flex-col space-y-3 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-2">
                                    <div className="w-fit flex-shrink-0 rounded-lg bg-accent p-2">
                                        <Gift className="h-4 w-4 text-accent-foreground" />
                                    </div>
                                    <div className="min-w-0 flex-1">
                                        <CardTitle className="text-lg sm:text-xl">
                                            Country Benefits
                                        </CardTitle>
                                        <p className="mt-1 text-xs text-muted-foreground sm:text-sm">
                                            Advantages of studying in this country
                                        </p>
                                    </div>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-2">
                                    <Label htmlFor="country_benefits" className="text-sm font-medium">
                                        Benefits & Advantages
                                    </Label>
                                    <Textarea
                                        id="country_benefits"
                                        value={data.country_benefits}
                                        onChange={handleInputChange('country_benefits')}
                                        placeholder="Enter benefits, advantages, quality of education, cultural opportunities, career prospects, and other positive aspects..."
                                        rows={4}
                                        className="resize-none"
                                    />
                                    {errors.country_benefits && <p className="text-sm text-destructive">{errors.country_benefits}</p>}
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    {/* Application Steps Card */}
                    <Card>
                        <CardHeader>
                            <div className="flex min-w-0 flex-col space-y-3 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-2">
                                <div className="w-fit flex-shrink-0 rounded-lg bg-secondary p-2">
                                    <Settings className="h-4 w-4 text-secondary-foreground" />
                                </div>
                                <div className="min-w-0 flex-1">
                                    <CardTitle className="text-lg sm:text-xl">
                                        Application Process Steps
                                    </CardTitle>
                                    <p className="mt-1 text-xs text-muted-foreground sm:text-sm">
                                        Configure the application process workflow
                                    </p>
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-4">
                                <div className="space-y-2">
                                    <Label htmlFor="application_process_ids" className="text-sm font-medium">
                                        Application Steps
                                    </Label>
                                    <div className="w-full sm:w-1/2">
                                        <MultiSelect
                                            options={applicationProcesses.map((process) => ({
                                                label: process.name,
                                                value: process.id,
                                                disabled: process.id === newProcessId,
                                            }))}
                                            onValueChange={handleProcessChange}
                                            defaultValue={data.application_process_ids}
                                            placeholder="Select application steps"
                                            className="w-full"
                                            resetOnDefaultValueChange={false}
                                        />
                                    </div>
                                    <p className="text-xs text-muted-foreground">
                                        The "New" step is automatically included and cannot be removed
                                    </p>
                                    {errors.application_process_ids && <p className="text-sm text-destructive">{errors.application_process_ids}</p>}
                                </div>

                                {data.application_process_ids.length > 0 && (
                                    <div className="rounded-lg p-4">
                                        <h4 className="mb-2 text-sm font-medium">Selected Steps:</h4>
                                        <div className="flex flex-wrap gap-2">
                                            {data.application_process_ids.map((processId) => {
                                                const process = applicationProcesses.find((p) => p.id === processId);
                                                return process ? (
                                                    <Badge
                                                        key={processId}
                                                        variant={processId === newProcessId ? 'outline' : 'default'}
                                                        className="text-xs"
                                                    >
                                                        {process.name}
                                                        {processId === newProcessId && ' (Fixed)'}
                                                    </Badge>
                                                ) : null;
                                            })}
                                        </div>
                                    </div>
                                )}
                            </div>
                        </CardContent>
                    </Card>

                    {/* Action Buttons */}
                    <div className="flex min-w-0 flex-col items-start justify-between space-y-4 border-t pt-4 sm:flex-row sm:items-center sm:space-y-0 sm:pt-6">
                        <div className="text-center text-xs text-muted-foreground sm:text-left sm:text-sm">
                            Last updated: {new Date(representingCountry.country.name).toLocaleDateString() || 'N/A'}
                        </div>
                        <div className="flex w-full flex-col space-y-2 sm:w-auto sm:flex-row sm:space-y-0 sm:space-x-3">
                            <Button
                                type="button"
                                variant="outline"
                                className="w-full sm:w-auto"
                                onClick={() => window.history.back()}
                            >
                                Cancel
                            </Button>
                            <Button type="submit" disabled={processing} className="w-full sm:w-auto">
                                {processing ? 'Saving...' : 'Save Changes'}
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
