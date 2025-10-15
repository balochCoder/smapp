import ApplicationProcessController from '@/actions/App/Http/Controllers/ApplicationProcessController';
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
import * as applicationProcesses from '@/routes/application-processes';

interface ParentProcess {
    id: string;
    name: string;
}

interface Country {
    id: string;
    name: string;
    flag: string;
}

interface RepresentingCountry {
    id: string;
    country: Country;
}

interface Props {
    parentProcesses: ParentProcess[];
    representingCountries: RepresentingCountry[];
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
    {
        title: 'Create',
        href: applicationProcesses.create().url,
    },
];

export default function Create({
    parentProcesses,
    representingCountries,
}: Props) {
    const [parentId, setParentId] = useState('');
    const [selectedCountries, setSelectedCountries] = useState<string[]>([]);

    const toggleCountry = (countryId: string) => {
        setSelectedCountries((prev) =>
            prev.includes(countryId)
                ? prev.filter((id) => id !== countryId)
                : [...prev, countryId]
        );
    };

    const isSubProcess = !!parentId;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Create Application Process" />

            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                <HeadingSmall
                    title="Create Application Process"
                    description="Add a new application workflow process or sub-process"
                />

                <Form
                    {...ApplicationProcessController.store.form()}
                    className="space-y-6"
                >
                    {({ processing, errors }) => (
                        <>
                            <Card>
                                <CardHeader>
                                    <CardTitle>Process Information</CardTitle>
                                    <CardDescription>
                                        Define the process details and hierarchy
                                    </CardDescription>
                                </CardHeader>
                                <CardContent className="space-y-6">
                                    <div className="grid gap-2">
                                        <Label htmlFor="parent_id">
                                            Parent Process (Optional)
                                        </Label>
                                        <Select
                                            name="parent_id"
                                            value={parentId}
                                            onValueChange={setParentId}
                                        >
                                            <SelectTrigger>
                                                <SelectValue placeholder="None (Main Process)" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                {parentProcesses.map(
                                                    (process) => (
                                                        <SelectItem
                                                            key={process.id}
                                                            value={process.id}
                                                        >
                                                            {process.name}
                                                        </SelectItem>
                                                    )
                                                )}
                                            </SelectContent>
                                        </Select>
                                        <InputError message={errors.parent_id} />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="name">
                                            Process Name *
                                        </Label>
                                        <Input
                                            id="name"
                                            name="name"
                                            placeholder="e.g., Application Submission"
                                            required
                                        />
                                        <InputError message={errors.name} />
                                    </div>

                                    <div className="grid gap-2">
                                        <Label htmlFor="description">
                                            Description
                                        </Label>
                                        <textarea
                                            id="description"
                                            name="description"
                                            className="w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 min-h-[100px]"
                                            placeholder="Describe this process..."
                                        />
                                        <InputError message={errors.description} />
                                    </div>

                                    <div className="grid gap-4 md:grid-cols-2">
                                        <div className="grid gap-2">
                                            <Label htmlFor="order">
                                                Order *
                                            </Label>
                                            <Input
                                                id="order"
                                                name="order"
                                                type="number"
                                                defaultValue="1"
                                                min="0"
                                                required
                                            />
                                            <InputError message={errors.order} />
                                        </div>

                                        <div className="flex items-end pb-2">
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
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>

                            {!isSubProcess && (
                                <Card>
                                    <CardHeader>
                                        <CardTitle>
                                            Assign to Representing Countries
                                        </CardTitle>
                                        <CardDescription>
                                            Select which countries should use
                                            this process (main processes only)
                                        </CardDescription>
                                    </CardHeader>
                                    <CardContent>
                                        <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                                            {representingCountries.map(
                                                (repCountry) => (
                                                    <div
                                                        key={repCountry.id}
                                                        className="flex items-start space-x-2"
                                                    >
                                                        <Checkbox
                                                            id={`country-${repCountry.id}`}
                                                            name="representing_country_ids[]"
                                                            value={repCountry.id}
                                                            checked={selectedCountries.includes(
                                                                repCountry.id
                                                            )}
                                                            onCheckedChange={() =>
                                                                toggleCountry(
                                                                    repCountry.id
                                                                )
                                                            }
                                                        />
                                                        <Label
                                                            htmlFor={`country-${repCountry.id}`}
                                                            className="cursor-pointer flex items-center gap-2"
                                                        >
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
                                                                    className="h-4 w-6 rounded object-cover"
                                                                />
                                                            )}
                                                            {
                                                                repCountry.country
                                                                    .name
                                                            }
                                                        </Label>
                                                    </div>
                                                )
                                            )}
                                        </div>
                                    </CardContent>
                                </Card>
                            )}

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
                                        : 'Create Application Process'}
                                </Button>
                            </div>
                        </>
                    )}
                </Form>
            </div>
        </AppLayout>
    );
}
