import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Edit, ArrowLeft } from 'lucide-react';
import { dashboard } from '@/routes';
import * as representingCountries from '@/routes/representing-countries';

interface Country {
    id: string;
    name: string;
    flag: string;
}

interface SubProcess {
    id: string;
    name: string;
    description: string;
    order: number;
}

interface ApplicationProcess {
    id: string;
    name: string;
    description: string;
    order: number;
    sub_processes: SubProcess[];
}

interface RepresentingCountry {
    id: string;
    monthly_living_cost: string | null;
    visa_requirements: string | null;
    part_time_work_details: string | null;
    country_benefits: string | null;
    is_active: boolean;
    country: Country;
    application_processes: ApplicationProcess[];
}

interface Props {
    representingCountry: RepresentingCountry;
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

export default function Show({ representingCountry }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={representingCountry.country.name} />

            <div className="flex h-full flex-1 flex-col gap-4 p-4">
                <div className="flex items-center justify-between">
                    <div className="flex items-center gap-4">
                        <Link href={representingCountries.index()}>
                            <Button variant="outline" size="icon">
                                <ArrowLeft className="h-4 w-4" />
                            </Button>
                        </Link>
                        <div className="flex items-center gap-3">
                            {representingCountry.country.flag && (
                                <img
                                    src={representingCountry.country.flag}
                                    alt={representingCountry.country.name}
                                    className="h-12 w-16 rounded object-cover"
                                />
                            )}
                            <div>
                                <h1 className="text-2xl font-bold tracking-tight">
                                    {representingCountry.country.name}
                                </h1>
                                <p className="text-muted-foreground">
                                    Representing Country Details
                                </p>
                            </div>
                        </div>
                    </div>
                    <div className="flex gap-2">
                        <Badge
                            variant={
                                representingCountry.is_active
                                    ? 'default'
                                    : 'secondary'
                            }
                        >
                            {representingCountry.is_active
                                ? 'Active'
                                : 'Inactive'}
                        </Badge>
                        <Link
                            href={representingCountries.edit(
                                representingCountry.id
                            )}
                        >
                            <Button>
                                <Edit className="mr-2 h-4 w-4" />
                                Edit
                            </Button>
                        </Link>
                    </div>
                </div>

                <div className="grid gap-4 md:grid-cols-2">
                    <Card>
                        <CardHeader>
                            <CardTitle>Financial Information</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-2">
                                <div>
                                    <p className="text-sm font-medium">
                                        Monthly Living Cost
                                    </p>
                                    <p className="text-2xl font-bold">
                                        {representingCountry.monthly_living_cost
                                            ? `$${representingCountry.monthly_living_cost}`
                                            : 'Not specified'}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Application Processes</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div className="space-y-2">
                                {representingCountry.application_processes
                                    .length > 0 ? (
                                    representingCountry.application_processes.map(
                                        (process) => (
                                            <div
                                                key={process.id}
                                                className="space-y-1"
                                            >
                                                <Badge variant="outline">
                                                    {process.order}.{' '}
                                                    {process.name}
                                                </Badge>
                                                {process.sub_processes?.length >
                                                    0 && (
                                                    <div className="ml-4 space-y-1">
                                                        {process.sub_processes.map(
                                                            (subProcess) => (
                                                                <Badge
                                                                    key={
                                                                        subProcess.id
                                                                    }
                                                                    variant="secondary"
                                                                    className="text-xs ml-2"
                                                                >
                                                                    {
                                                                        subProcess.order
                                                                    }
                                                                    .{' '}
                                                                    {
                                                                        subProcess.name
                                                                    }
                                                                </Badge>
                                                            )
                                                        )}
                                                    </div>
                                                )}
                                            </div>
                                        )
                                    )
                                ) : (
                                    <p className="text-sm text-muted-foreground">
                                        No application processes assigned
                                    </p>
                                )}
                            </div>
                        </CardContent>
                    </Card>

                    <Card className="md:col-span-2">
                        <CardHeader>
                            <CardTitle>Visa Requirements</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p className="text-sm whitespace-pre-wrap">
                                {representingCountry.visa_requirements ||
                                    'Not specified'}
                            </p>
                        </CardContent>
                    </Card>

                    <Card className="md:col-span-2">
                        <CardHeader>
                            <CardTitle>Part-Time Work Details</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p className="text-sm whitespace-pre-wrap">
                                {representingCountry.part_time_work_details ||
                                    'Not specified'}
                            </p>
                        </CardContent>
                    </Card>

                    <Card className="md:col-span-2">
                        <CardHeader>
                            <CardTitle>Country Benefits</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <p className="text-sm whitespace-pre-wrap">
                                {representingCountry.country_benefits ||
                                    'Not specified'}
                            </p>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
