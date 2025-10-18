import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import * as adminRepresentingCountries from '@/routes/admin/representing-countries';
import * as branchRepresentingCountries from '@/routes/branch/representing-countries';
import * as counsellorRepresentingCountries from '@/routes/counsellor/representing-countries';
import { SharedData, type NavItem } from '@/types';
import { usePage, Link } from '@inertiajs/react';
import { BookOpen, Folder, LayoutGrid, Globe } from 'lucide-react';
import AppLogo from './app-logo';

const footerNavItems: NavItem[] = [
    {
        title: 'Repository',
        href: 'https://github.com/laravel/react-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits#react',
        icon: BookOpen,
    },
];

export function AppSidebar() {
    const { auth } = usePage<SharedData>().props;
    const user = auth?.user;

    // Determine the base path based on user's primary role
    const getRepresentingCountriesRoute = () => {
        if (!user || !user.roles) {
            return null;
        }

        if (user.roles.includes('Admin')) {
            return adminRepresentingCountries.index();
        }
        if (user.roles.includes('BranchManager')) {
            return branchRepresentingCountries.index();
        }
        if (user.roles.includes('Counsellor')) {
            return counsellorRepresentingCountries.index();
        }
        // Default to null if no role matches
        return null;
    };

    const representingCountriesRoute = getRepresentingCountriesRoute();

    const mainNavItems: NavItem[] = [
        {
            title: 'Dashboard',
            href: dashboard(),
            icon: LayoutGrid,
        },
        ...(representingCountriesRoute
            ? [
                  {
                      title: 'Representing Countries',
                      href: representingCountriesRoute,
                      icon: Globe,
                  },
              ]
            : []),
    ];

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
