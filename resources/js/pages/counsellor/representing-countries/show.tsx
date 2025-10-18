import { Badge } from '@/components/ui/badge';
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

interface RepresentingCountry {
    id: string;
    name: string;
    currency: string;
    country: {
        name: string;
        flag: string;
    };
    rep_country_statuses: Array<{
        id: string;
        status_name: string;
        custom_name: string | null;
        order: number;
    }>;
}

interface Props {
    representingCountry: RepresentingCountry;
    permissions: {
        canEdit: boolean;
        canDelete: boolean;
        canManageStatus: boolean;
    };
}

export default function CounsellorRepresentingCountryShow({
    representingCountry,
}: Props) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Counsellor Dashboard',
            href: '/counsellor/dashboard',
        },
        {
            title: 'Representing Countries',
            href: '/counsellor/representing-countries',
        },
        {
            title: representingCountry.name,
            href: `/counsellor/representing-countries/${representingCountry.id}`,
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`${representingCountry.name} - Quick View`} />

            <div className="flex h-full flex-col gap-6 p-6">
                <div className="flex items-center gap-3">
                    <span className="text-4xl">
                        {representingCountry.country.flag}
                    </span>
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight">
                            {representingCountry.name}
                        </h1>
                        <p className="text-muted-foreground">
                            Quick reference view
                        </p>
                    </div>
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Application Process Steps</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="grid gap-2 md:grid-cols-2">
                            {representingCountry.rep_country_statuses.map(
                                (status) => (
                                    <div
                                        key={status.id}
                                        className="flex items-center justify-between rounded-lg border p-3"
                                    >
                                        <span className="font-medium">
                                            {status.custom_name ||
                                                status.status_name}
                                        </span>
                                        <Badge variant="secondary" className="text-xs">
                                            Step {status.order}
                                        </Badge>
                                    </div>
                                )
                            )}
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Country Details</CardTitle>
                    </CardHeader>
                    <CardContent className="space-y-3">
                        <div>
                            <p className="text-sm text-muted-foreground">
                                Country
                            </p>
                            <p className="text-base font-medium">
                                {representingCountry.country.name}
                            </p>
                        </div>
                        <div>
                            <p className="text-sm text-muted-foreground">
                                Currency
                            </p>
                            <p className="text-base font-medium">
                                {representingCountry.currency}
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}

