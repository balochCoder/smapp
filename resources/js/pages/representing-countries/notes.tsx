import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { dashboard } from '@/routes';
import * as representingCountries from '@/routes/representing-countries';

interface Country {
    id: string;
    name: string;
    flag: string;
}

interface RepCountryStatus {
    id: number;
    status_name: string;
    custom_name: string | null;
    notes: string | null;
}

interface RepresentingCountry {
    id: string;
    country: Country;
    rep_country_statuses: RepCountryStatus[];
}

interface Props {
    representingCountry: RepresentingCountry;
}

export default function Notes({ representingCountry }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        status_notes: representingCountry.rep_country_statuses.map(
            (status) => ({
                id: status.id,
                notes: status.notes || '',
            })
        ),
    });

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: dashboard().url,
        },
        {
            title: 'Representing Countries',
            href: representingCountries.index().url,
        },
        {
            title: representingCountry.country.name,
            href: representingCountries.index().url,
        },
    ];

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(representingCountries.updateNotes(representingCountry.id).url);
    };

    const updateNotes = (statusId: number, notes: string) => {
        setData(
            'status_notes',
            data.status_notes.map((sn) =>
                sn.id === statusId ? { ...sn, notes } : sn
            )
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head
                title={`Status Notes - ${representingCountry.country.name}`}
            />

            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <div>
                    <h1 className="text-3xl font-bold">
                        Application Status Notes
                    </h1>
                    <p className="mt-2 text-muted-foreground">
                        Add notes for each application status step for{' '}
                        <span className="font-medium">
                            {representingCountry.country.flag}{' '}
                            {representingCountry.country.name}
                        </span>
                    </p>
                </div>

                <form onSubmit={handleSubmit} className="space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>Status Notes</CardTitle>
                            <CardDescription>
                                Add detailed notes, guidelines, or instructions
                                for each application status
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-6">
                            {representingCountry.rep_country_statuses.map(
                                (status, index) => (
                                    <div key={status.id} className="space-y-2">
                                        <Label htmlFor={`notes-${status.id}`}>
                                            <span className="inline-flex items-center gap-2">
                                                <span className="flex h-6 w-6 items-center justify-center rounded-full bg-primary/10 text-xs font-semibold text-primary">
                                                    {index + 1}
                                                </span>
                                                {status.custom_name ||
                                                    status.status_name}
                                            </span>
                                        </Label>
                                        <Textarea
                                            id={`notes-${status.id}`}
                                            value={
                                                data.status_notes.find(
                                                    (sn) => sn.id === status.id
                                                )?.notes || ''
                                            }
                                            onChange={(e) =>
                                                updateNotes(
                                                    status.id,
                                                    e.target.value
                                                )
                                            }
                                            placeholder={`Add notes for ${
                                                status.custom_name ||
                                                status.status_name
                                            }...`}
                                            rows={4}
                                        />
                                    </div>
                                )
                            )}
                        </CardContent>
                    </Card>

                    <div className="flex justify-end gap-3">
                        <Button type="submit" disabled={processing}>
                            {processing ? 'Saving...' : 'Save Notes'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
