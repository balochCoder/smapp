import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Edit, ArrowLeft, ChevronRight } from 'lucide-react';
import { dashboard } from '@/routes';
import * as applicationProcesses from '@/routes/application-processes';
import * as representingCountries from '@/routes/representing-countries';

interface Country {
    id: string;
    name: string;
    flag: string;
}

interface RepresentingCountry {
    id: string;
    country: Country;
}

interface ParentProcess {
    id: string;
    name: string;
}

interface SubProcess {
    id: string;
    name: string;
    description: string;
    order: number;
    is_active: boolean;
}

interface ApplicationProcess {
    id: string;
    name: string;
    description: string | null;
    order: number;
    is_active: boolean;
    parent: ParentProcess | null;
    sub_processes: SubProcess[];
    representing_countries: RepresentingCountry[];
}

interface Props {
    process: ApplicationProcess;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
    {
        title: 'Application Processes',
        href: applicationProcesses.index().url,
    },
];

export default function Show({ process }: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={process.name} />

            <div className="flex h-full flex-1 flex-col gap-4 p-4">
                <div className="flex items-center justify-between">
                    <div className="flex items-center gap-4">
                        <Link href={applicationProcesses.index()}>
                            <Button variant="outline" size="icon">
                                <ArrowLeft className="h-4 w-4" />
                            </Button>
                        </Link>
                        <div>
                            <div className="flex items-center gap-2">
                                <Badge variant="outline">{process.order}</Badge>
                                <h1 className="text-2xl font-bold tracking-tight">
                                    {process.name}
                                </h1>
                            </div>
                            {process.parent && (
                                <p className="text-sm text-muted-foreground">
                                    Sub-process of: {process.parent.name}
                                </p>
                            )}
                        </div>
                    </div>
                    <div className="flex gap-2">
                        <Badge
                            variant={
                                process.is_active ? 'default' : 'secondary'
                            }
                        >
                            {process.is_active ? 'Active' : 'Inactive'}
                        </Badge>
                        <Link href={applicationProcesses.edit(process.id)}>
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
                            <CardTitle>Process Details</CardTitle>
                        </CardHeader>
                        <CardContent className="space-y-4">
                            <div>
                                <p className="text-sm font-medium">
                                    Description
                                </p>
                                <p className="text-sm text-muted-foreground">
                                    {process.description ||
                                        'No description provided'}
                                </p>
                            </div>
                            <div>
                                <p className="text-sm font-medium">Order</p>
                                <p className="text-sm text-muted-foreground">
                                    {process.order}
                                </p>
                            </div>
                        </CardContent>
                    </Card>

                    {process.sub_processes.length > 0 && (
                        <Card>
                            <CardHeader>
                                <CardTitle>Sub-Processes</CardTitle>
                                <CardDescription>
                                    {process.sub_processes.length} sub-process
                                    {process.sub_processes.length !== 1
                                        ? 'es'
                                        : ''}
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-2">
                                    {process.sub_processes.map((subProcess) => (
                                        <div
                                            key={subProcess.id}
                                            className="flex items-center gap-2 p-2 rounded-lg border"
                                        >
                                            <ChevronRight className="h-4 w-4 text-muted-foreground" />
                                            <Badge
                                                variant="secondary"
                                                className="text-xs"
                                            >
                                                {subProcess.order}
                                            </Badge>
                                            <div className="flex-1">
                                                <p className="text-sm font-medium">
                                                    {subProcess.name}
                                                </p>
                                                <p className="text-xs text-muted-foreground">
                                                    {subProcess.description}
                                                </p>
                                            </div>
                                            <Badge
                                                variant={
                                                    subProcess.is_active
                                                        ? 'default'
                                                        : 'secondary'
                                                }
                                                className="text-xs"
                                            >
                                                {subProcess.is_active
                                                    ? 'Active'
                                                    : 'Inactive'}
                                            </Badge>
                                        </div>
                                    ))}
                                </div>
                            </CardContent>
                        </Card>
                    )}

                    <Card
                        className={
                            process.sub_processes.length > 0
                                ? 'md:col-span-2'
                                : ''
                        }
                    >
                        <CardHeader>
                            <CardTitle>Representing Countries</CardTitle>
                            <CardDescription>
                                Countries that use this process
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className="flex flex-wrap gap-2">
                                {process.representing_countries.length > 0 ? (
                                    process.representing_countries.map(
                                        (repCountry) => (
                                            <Link
                                                key={repCountry.id}
                                                href={representingCountries.show(
                                                    repCountry.id
                                                )}
                                            >
                                                <Badge variant="outline">
                                                    {repCountry.country.flag && (
                                                        <img
                                                            src={
                                                                repCountry.country
                                                                    .flag
                                                            }
                                                            alt={
                                                                repCountry.country
                                                                    .name
                                                            }
                                                            className="h-3 w-5 rounded object-cover mr-1"
                                                        />
                                                    )}
                                                    {repCountry.country.name}
                                                </Badge>
                                            </Link>
                                        )
                                    )
                                ) : (
                                    <p className="text-sm text-muted-foreground">
                                        Not assigned to any country
                                    </p>
                                )}
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
