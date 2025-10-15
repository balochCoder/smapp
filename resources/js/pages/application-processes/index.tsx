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
import { Head, Link, router } from '@inertiajs/react';
import { PlusIcon, Edit, Trash2, ChevronRight } from 'lucide-react';
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
    is_active: boolean;
    sub_processes: SubProcess[];
    representing_countries: RepresentingCountry[];
}

interface Props {
    processes: ApplicationProcess[];
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

export default function Index({ processes }: Props) {
    const handleDelete = (id: string, processName: string) => {
        if (
            confirm(
                `Are you sure you want to delete "${processName}"? This will also delete all sub-processes.`
            )
        ) {
            router.delete(applicationProcesses.destroy(id).url);
        }
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Application Processes" />

            <div className="flex h-full flex-1 flex-col gap-4 p-4">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold tracking-tight">
                            Application Processes
                        </h1>
                        <p className="text-muted-foreground">
                            Manage application workflow processes and stages
                        </p>
                    </div>
                    <Link href={applicationProcesses.create()}>
                        <Button>
                            <PlusIcon className="mr-2 h-4 w-4" />
                            Add Process
                        </Button>
                    </Link>
                </div>

                <div className="space-y-4">
                    {processes.map((process) => (
                        <Card key={process.id}>
                            <CardHeader>
                                <div className="flex items-start justify-between">
                                    <div className="flex items-center gap-2">
                                        <Badge variant="outline">
                                            {process.order}
                                        </Badge>
                                        <div>
                                            <CardTitle>{process.name}</CardTitle>
                                            <CardDescription>
                                                {process.description}
                                            </CardDescription>
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-2">
                                        <Badge
                                            variant={
                                                process.is_active
                                                    ? 'default'
                                                    : 'secondary'
                                            }
                                        >
                                            {process.is_active
                                                ? 'Active'
                                                : 'Inactive'}
                                        </Badge>
                                        <Link
                                            href={applicationProcesses.edit(
                                                process.id
                                            )}
                                        >
                                            <Button variant="outline" size="sm">
                                                <Edit className="mr-2 h-4 w-4" />
                                                Edit
                                            </Button>
                                        </Link>
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            onClick={() =>
                                                handleDelete(
                                                    process.id,
                                                    process.name
                                                )
                                            }
                                        >
                                            <Trash2 className="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-4">
                                    {process.sub_processes.length > 0 && (
                                        <div>
                                            <p className="text-sm font-medium mb-2">
                                                Sub-Processes
                                            </p>
                                            <div className="space-y-2">
                                                {process.sub_processes.map(
                                                    (subProcess) => (
                                                        <div
                                                            key={subProcess.id}
                                                            className="flex items-center gap-2 pl-4 border-l-2"
                                                        >
                                                            <ChevronRight className="h-4 w-4 text-muted-foreground" />
                                                            <Badge
                                                                variant="secondary"
                                                                className="text-xs"
                                                            >
                                                                {subProcess.order}
                                                            </Badge>
                                                            <span className="text-sm">
                                                                {subProcess.name}
                                                            </span>
                                                        </div>
                                                    )
                                                )}
                                            </div>
                                        </div>
                                    )}

                                    <div>
                                        <p className="text-sm font-medium mb-2">
                                            Used in Countries
                                        </p>
                                        <div className="flex flex-wrap gap-2">
                                            {process.representing_countries
                                                .length > 0 ? (
                                                process.representing_countries.map(
                                                    (repCountry) => (
                                                        <Link
                                                            key={repCountry.id}
                                                            href={representingCountries.show(
                                                                repCountry.id
                                                            )}
                                                        >
                                                            <Badge variant="outline">
                                                                {repCountry.country
                                                                    .flag && (
                                                                    <img
                                                                        src={
                                                                            repCountry
                                                                                .country
                                                                                .flag
                                                                        }
                                                                        alt={
                                                                            repCountry
                                                                                .country
                                                                                .name
                                                                        }
                                                                        className="h-3 w-5 rounded object-cover mr-1"
                                                                    />
                                                                )}
                                                                {
                                                                    repCountry
                                                                        .country
                                                                        .name
                                                                }
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
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                {processes.length === 0 && (
                    <Card>
                        <CardContent className="flex flex-col items-center justify-center py-12">
                            <p className="text-muted-foreground mb-4">
                                No application processes created yet
                            </p>
                            <Link href={applicationProcesses.create()}>
                                <Button>
                                    <PlusIcon className="mr-2 h-4 w-4" />
                                    Add Your First Process
                                </Button>
                            </Link>
                        </CardContent>
                    </Card>
                )}
            </div>
        </AppLayout>
    );
}
