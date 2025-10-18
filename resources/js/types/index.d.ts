import { InertiaLinkProps } from '@inertiajs/react';
import { LucideIcon } from 'lucide-react';

export interface Auth {
    user: User | null;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    sidebarOpen: boolean;
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    two_factor_enabled?: boolean;
    created_at: string;
    updated_at: string;
    roles?: string[]; // User's role names (e.g., ['Admin', 'BranchManager'])
    permissions?: string[]; // User's permission names
    [key: string]: unknown; // This allows for additional properties...
}

export interface Country {
    id: string;
    name: string;
    flag: string;
    currency?: string;
}

export interface SubStatus {
    id: number;
    name: string;
    description: string | null;
    order: number;
    is_active: boolean;
}

export interface RepCountryStatus {
    id: number;
    status_name: string;
    custom_name: string | null;
    notes: string | null;
    order: number;
    is_active: boolean;
    sub_statuses?: SubStatus[];
}

export interface RepresentingCountry {
    id: string;
    country_id: string;
    monthly_living_cost: string | null;
    currency?: string;
    visa_requirements?: string | null;
    part_time_work_details?: string | null;
    country_benefits?: string | null;
    is_active: boolean;
    created_at: string;
    updated_at?: string;
    country: Country;
    rep_country_statuses?: RepCountryStatus[];
    application_processes?: ApplicationProcess[];
}

export interface ApplicationProcess {
    id: string;
    name: string;
    color: string;
}

export interface PaginatedData<T> {
    data: T[];
    meta: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        from: number;
        to: number;
    };
    links: {
        first: string | null;
        last: string | null;
        prev: string | null;
        next: string | null;
    };
}
