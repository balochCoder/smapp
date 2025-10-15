import RepresentingCountryController from '@/actions/App/Http/Controllers/RepresentingCountryController';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Checkbox } from '@/components/ui/checkbox';
import InputError from '@/components/input-error';
import HeadingSmall from '@/components/heading-small';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Form, Head } from '@inertiajs/react';
import { useState } from 'react';
import { dashboard } from '@/routes';
import * as representingCountries from '@/routes/representing-countries';

interface Country {
    id: string;
    name: string;
    flag: string;
}

interface ApplicationProcess {
    id: string;
    name: string;
    description: string;
}

interface Props {
    countries: Country[];
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
    {
        title: 'Create',
        href: representingCountries.create().url,
    },
];

export default function Create({ countries, applicationProcesses }: Props) {
    const [selectedCountry, setSelectedCountry] = useState('');
    const [selectedProcesses, setSelectedProcesses] = useState<string[]>([]);

    const toggleProcess = (processId: string) => {
        setSelectedProcesses((prev) =>
            prev.includes(processId)
                ? prev.filter((id) => id !== processId)
                : [...prev, processId]
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Add Representing Country" />

            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <HeadingSmall
                    title="Add Representing Country"
                    description="Add a new country that your organization represents"
                />

                <Form
                    {...RepresentingCountryController.store.form()}
                    className="space-y-6"
                >
                    {({ processing, errors }) => (
                        <>
                            <Card>
                                <CardHeader>
                                    <CardTitle>Country Information</CardTitle>
                                    <CardDescription>
                                        Select the country and provide basic
                                        details
                                    </CardDescription>
                                </CardHeader>
                                <CardContent className="space-y-6">
                                    <div className="grid gap-2">
                                        <Label htmlFor="country_id">
                                            Country *
                                        </Label>
                                        <Select
                                            name="country_id"
                                            value={selectedCountry}
                                            onValueChange={setSelectedCountry}
                                            required
                                        >
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select a country" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                {countries.map((country) => (
                                                    <SelectItem
                                                        key={country.id}
                                                        value={country.id}
                                                    >
                                                        <div className="flex items-center gap-2">
                                                            {country.flag && (
                                                                <img
                                                                    src={
                                                                        country.flag
                                                                    }
                                                                    alt={
                                                                        country.name
                                                                    }
                                                                    className="h-4 w-6 rounded object-cover"
                                                                />
                                                            )}
                                                            {country.name}
                                                        </div>
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                        <InputError message={errors.country_id} />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="monthly_living_cost">
                                            Monthly Living Cost (USD)
                                        </Label>
                                        <Input
                                            id="monthly_living_cost"
                                            name="monthly_living_cost"
                                            type="number"
                                            step="0.01"
                                            placeholder="e.g., 1200.00"
                                        />
                                        <InputError
                                            message={errors.monthly_living_cost}
                                        />
                                    </div>

                                    <div className="flex items-center space-x-2">
                                        <Checkbox
                                            id="is_active"
                                            name="is_active"
                                            defaultChecked={true}
                                        />
                                        <Label
                                            htmlFor="is_active"
                                            className="cursor-pointer"
                                        >
                                            Active
                                        </Label>
                                    </div>
                                </CardContent>
                            </Card>

                            <div className="grid gap-6 md:grid-cols-2">
                                <Card>
                                    <CardHeader>
                                        <CardTitle>Visa Requirements</CardTitle>
                                    </CardHeader>
                                    <CardContent>
                                        <div className="grid gap-2">
                                            <textarea
                                                name="visa_requirements"
                                                className="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 min-h-[120px]"
                                                placeholder="Describe visa requirements..."
                                            />
                                            <InputError
                                                message={errors.visa_requirements}
                                            />
                                        </div>
                                    </CardContent>
                                </Card>

                                <Card>
                                    <CardHeader>
                                        <CardTitle>
                                            Part-Time Work Details
                                        </CardTitle>
                                    </CardHeader>
                                    <CardContent>
                                        <div className="grid gap-2">
                                            <textarea
                                                name="part_time_work_details"
                                                className="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 min-h-[120px]"
                                                placeholder="Describe part-time work regulations..."
                                            />
                                            <InputError
                                                message={
                                                    errors.part_time_work_details
                                                }
                                            />
                                        </div>
                                    </CardContent>
                                </Card>
                            </div>

                            <Card>
                                <CardHeader>
                                    <CardTitle>Country Benefits</CardTitle>
                                    <CardDescription>
                                        Why should students choose this country?
                                    </CardDescription>
                                </CardHeader>
                                <CardContent>
                                    <div className="grid gap-2">
                                        <textarea
                                            name="country_benefits"
                                            className="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 min-h-[120px]"
                                            placeholder="Describe the benefits of studying in this country..."
                                        />
                                        <InputError
                                            message={errors.country_benefits}
                                        />
                                    </div>
                                </CardContent>
                            </Card>

                            <Card>
                                <CardHeader>
                                    <CardTitle>Application Processes</CardTitle>
                                    <CardDescription>
                                        Select the application processes for this
                                        country
                                    </CardDescription>
                                </CardHeader>
                                <CardContent>
                                    <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                                        {applicationProcesses.map((process) => (
                                            <div
                                                key={process.id}
                                                className="flex items-start space-x-2"
                                            >
                                                <Checkbox
                                                    id={`process-${process.id}`}
                                                    name="application_process_ids[]"
                                                    value={process.id}
                                                    checked={selectedProcesses.includes(
                                                        process.id
                                                    )}
                                                    onCheckedChange={() =>
                                                        toggleProcess(process.id)
                                                    }
                                                />
                                                <div className="grid gap-1.5 leading-none">
                                                    <Label
                                                        htmlFor={`process-${process.id}`}
                                                        className="cursor-pointer font-medium"
                                                    >
                                                        {process.name}
                                                    </Label>
                                                    <p className="text-sm text-muted-foreground">
                                                        {process.description}
                                                    </p>
                                                </div>
                                            </div>
                                        ))}
                                    </div>
                                </CardContent>
                            </Card>

                            <div className="flex justify-end gap-2">
                                <Button
                                    type="button"
                                    variant="outline"
                                    onClick={() => window.history.back()}
                                >
                                    Cancel
                                </Button>
                                <Button type="submit" disabled={processing}>
                                    {processing
                                        ? 'Creating...'
                                        : 'Create Representing Country'}
                                </Button>
                            </div>
                        </>
                    )}
                </Form>
            </div>
        </AppLayout>
    );
}
