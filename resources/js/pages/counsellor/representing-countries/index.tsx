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
import { Eye } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Counsellor Dashboard',
        href: '/counsellor/dashboard',
    },
    {
        title: 'Representing Countries',
        href: '/counsellor/representing-countries',
    },
];

interface RepresentingCountry {
    id: string;
    name: string;
    country: {
        name: string;
        flag: string;
    };
    is_active: boolean;
    statuses: Array<{
        id: string;
        status_name: string;
        custom_name: string | null;
    }>;
}

interface Props {
    representingCountries: RepresentingCountry[];
    availableCountries: Array<{
        id: string;
        name: string;
        flag: string;
    }>;
    filters: {
        country?: string;
    };
    permissions: {
        canCreate: boolean;
        canEdit: boolean;
        canDelete: boolean;
        canManageStatus: boolean;
    };
}

export default function CounsellorRepresentingCountriesIndex({
    representingCountries,
}: Props) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Representing Countries - Counsellor View" />

            <div className="flex h-full flex-col gap-6 p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight">
                            Representing Countries
                        </h1>
                        <p className="text-muted-foreground">
                            Quick reference for available countries
                        </p>
                    </div>
                </div>

                <div className="grid gap-3 md:grid-cols-2 lg:grid-cols-4">
                    {representingCountries.map((country) => (
                        <Card key={country.id} className="hover:shadow-md transition-shadow">
                            <CardHeader className="pb-3">
                                <div className="flex items-center gap-2">
                                    <span className="text-2xl">
                                        {country.country.flag}
                                    </span>
                                    <CardTitle className="text-base">
                                        {country.name}
                                    </CardTitle>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <div className="space-y-2">
                                    <Badge variant="outline" className="text-xs bg-green-50 text-green-700 dark:bg-green-950/50 dark:text-green-400">
                                        {country.statuses.length} Process Steps
                                    </Badge>
                                    <Link
                                        href={`/counsellor/representing-countries/${country.id}`}
                                    >
                                        <Button variant="ghost" size="sm" className="w-full">
                                            <Eye className="mr-2 size-3" />
                                            View
                                        </Button>
                                    </Link>
                                </div>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                {representingCountries.length === 0 && (
                    <Card>
                        <CardContent className="py-10 text-center">
                            <p className="text-muted-foreground">
                                No active representing countries found.
                            </p>
                        </CardContent>
                    </Card>
                )}
            </div>
        </AppLayout>
    );
}

