import { Head } from '@inertiajs/react';

import AppearanceTabs from '@/components/appearance-tabs';
import ThemeSwitcher from '@/components/theme-switcher';
import HeadingSmall from '@/components/heading-small';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { edit as editAppearance } from '@/routes/appearance';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Appearance settings',
        href: editAppearance().url,
    },
];

export default function Appearance() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Appearance settings" />

            <SettingsLayout>
                <div className="space-y-8">
                    <div className="space-y-6">
                        <HeadingSmall
                            title="Light / Dark Mode"
                            description="Select your preferred appearance mode"
                        />
                        <AppearanceTabs />
                    </div>

                    <div className="space-y-6">
                        <HeadingSmall
                            title="Color Theme"
                            description="Choose your preferred color palette (works with both light and dark modes)"
                        />
                        <ThemeSwitcher />
                    </div>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
