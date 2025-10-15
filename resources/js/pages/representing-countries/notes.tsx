import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Textarea } from '@/components/ui/textarea';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm } from '@inertiajs/react';
import { ArrowLeft, Save } from 'lucide-react';
import { dashboard } from '@/routes';
import * as representingCountries from '@/routes/representing-countries';

interface ApplicationProcess {
    id: string;
    name: string;
    description: string | null;
    order: number;
    is_active: boolean;
}

interface Country {
    id: string;
    name: string;
    flag: string;
}

interface RepresentingCountry {
    id: string;
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
    {
        title: 'Notes',
        href: '#',
    },
];

export default function Notes({ representingCountry }: Props) {
    const { data, setData, post, processing, errors } = useForm({
        process_descriptions: representingCountry.application_processes.reduce(
            (acc, process) => ({
                ...acc,
                [process.id]: process.description || '',
            }),
            {} as Record<string, string>
        ),
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        post(representingCountries.updateNotes(representingCountry.id).url);
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`Notes - ${representingCountry.country.name}`} />

            <div className="flex h-full flex-1 flex-col gap-4 p-4">
                <div className="flex items-center justify-between">
                    <div className="flex items-center gap-4">
                        <Button
                            variant="outline"
                            size="sm"
                            onClick={() => router.visit(representingCountries.index().url)}
                        >
                            <ArrowLeft className="mr-2 h-4 w-4" />
                            Back
                        </Button>
                        <div>
                            <div className="flex items-center gap-3">
                                {representingCountry.country.flag && (
                                    <img
                                        src={representingCountry.country.flag}
                                        alt={representingCountry.country.name}
                                        className="h-8 w-12 rounded object-cover border"
                                    />
                                )}
                                <div>
                                    <h1 className="text-2xl font-bold tracking-tight">
                                        Application Process Notes
                                    </h1>
                                    <p className="text-muted-foreground">
                                        Add descriptions for {representingCountry.country.name} application processes
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form onSubmit={handleSubmit} className="space-y-6">
                    <div className="grid gap-4">
                        {representingCountry.application_processes.map((process) => (
                            <Card key={process.id}>
                                <CardHeader>
                                    <CardTitle className="text-lg">
                                        {process.order}. {process.name}
                                    </CardTitle>
                                    <CardDescription>
                                        Add detailed description for this application process step
                                    </CardDescription>
                                </CardHeader>
                                <CardContent>
                                    <div className="space-y-2">
                                        <Label htmlFor={`description-${process.id}`}>
                                            Description
                                        </Label>
                                        <Textarea
                                            id={`description-${process.id}`}
                                            value={data.process_descriptions[process.id] || ''}
                                            onChange={(e) =>
                                                setData('process_descriptions', {
                                                    ...data.process_descriptions,
                                                    [process.id]: e.target.value,
                                                })
                                            }
                                            placeholder="Enter detailed description for this process step..."
                                            rows={4}
                                            className="resize-none"
                                        />
                                        {errors[`process_descriptions.${process.id}`] && (
                                            <p className="text-sm text-red-600">
                                                {errors[`process_descriptions.${process.id}`]}
                                            </p>
                                        )}
                                    </div>
                                </CardContent>
                            </Card>
                        ))}
                    </div>

                    <div className="flex justify-end gap-2">
                        <Button
                            type="button"
                            variant="outline"
                            onClick={() => router.visit(representingCountries.index().url)}
                        >
                            Cancel
                        </Button>
                        <Button type="submit" disabled={processing}>
                            <Save className="mr-2 h-4 w-4" />
                            {processing ? 'Saving...' : 'Save Notes'}
                        </Button>
                    </div>
                </form>
            </div>
        </AppLayout>
    );
}
