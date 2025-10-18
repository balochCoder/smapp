import { create, store } from '@/routes/register';
import { Head, Link, router } from '@inertiajs/react';
import { Building2, Check, CreditCard, User } from 'lucide-react';
import { useState } from 'react';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Spinner } from '@/components/ui/spinner';
import AuthLayout from '@/layouts/auth-layout';

type SubscriptionPlan = {
    value: string;
    name: string;
    description: string;
    price: string;
    features: string[];
};

type Props = {
    subscriptionPlans: SubscriptionPlan[];
};

type FormData = {
    organization_name: string;
    organization_slug: string;
    organization_email: string;
    organization_phone: string;
    admin_name: string;
    admin_email: string;
    admin_password: string;
    admin_password_confirmation: string;
    subscription_plan: string;
    terms_accepted: boolean | string;
};

export default function RegisterOrganization({ subscriptionPlans }: Props) {
    const [step, setStep] = useState(1);
    const [processing, setProcessing] = useState(false);
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [formData, setFormData] = useState<Partial<FormData>>({
        subscription_plan: 'trial',
    });
    const totalSteps = 3;

    const updateField = (field: keyof FormData, value: string | boolean) => {
        setFormData((prev) => ({ ...prev, [field]: value }));
        // Clear error for this field
        if (errors[field]) {
            setErrors((prev) => {
                const newErrors = { ...prev };
                delete newErrors[field];
                return newErrors;
            });
        }
    };

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        setProcessing(true);

        router.post(store().url, formData as FormData, {
            onError: (errors) => {
                setErrors(errors);
                setProcessing(false);
            },
            onSuccess: () => {
                setProcessing(false);
            },
        });
    };

    return (
        <AuthLayout
            title="Register Your Organization"
            description="Create your study abroad consultancy account"
        >
            <Head title="Register Organization" />

            {/* Progress Indicator */}
            <div className="mb-8">
                <div className="flex items-center justify-between">
                    {[1, 2, 3].map((s) => (
                        <div key={s} className="flex items-center">
                            <div
                                className={`flex h-10 w-10 items-center justify-center rounded-full border-2 ${
                                    s === step
                                        ? 'border-primary bg-primary text-primary-foreground'
                                        : s < step
                                          ? 'border-primary bg-primary text-primary-foreground'
                                          : 'border-muted-foreground/30 bg-background'
                                }`}
                            >
                                {s < step ? (
                                    <Check className="h-5 w-5" />
                                ) : s === 1 ? (
                                    <Building2 className="h-5 w-5" />
                                ) : s === 2 ? (
                                    <User className="h-5 w-5" />
                                ) : (
                                    <CreditCard className="h-5 w-5" />
                                )}
                            </div>
                            {s < totalSteps && (
                                <div
                                    className={`mx-2 h-0.5 w-12 sm:w-20 ${
                                        s < step
                                            ? 'bg-primary'
                                            : 'bg-muted-foreground/30'
                                    }`}
                                />
                            )}
                        </div>
                    ))}
                </div>
                <div className="mt-2 flex justify-between text-xs sm:text-sm">
                    <span
                        className={
                            step === 1
                                ? 'font-semibold text-primary'
                                : 'text-muted-foreground'
                        }
                    >
                        Organization
                    </span>
                    <span
                        className={
                            step === 2
                                ? 'font-semibold text-primary'
                                : 'text-muted-foreground'
                        }
                    >
                        Admin User
                    </span>
                    <span
                        className={
                            step === 3
                                ? 'font-semibold text-primary'
                                : 'text-muted-foreground'
                        }
                    >
                        Plan
                    </span>
                </div>
            </div>

            <form onSubmit={handleSubmit} className="flex flex-col gap-6">
                {/* Step 1: Organization Information */}
                {step === 1 && (
                    <div className="grid gap-4">
                        <div className="grid gap-2">
                            <Label htmlFor="organization_name">
                                Organization Name *
                            </Label>
                            <Input
                                id="organization_name"
                                name="organization_name"
                                type="text"
                                required
                                autoFocus
                                placeholder="Acme Education Consultancy"
                                value={formData.organization_name || ''}
                                onChange={(e) =>
                                    updateField(
                                        'organization_name',
                                        e.target.value,
                                    )
                                }
                            />
                            <InputError message={errors.organization_name} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="organization_slug">
                                Organization Slug *
                            </Label>
                            <Input
                                id="organization_slug"
                                name="organization_slug"
                                type="text"
                                required
                                placeholder="acme-education"
                                value={formData.organization_slug || ''}
                                onChange={(e) =>
                                    updateField(
                                        'organization_slug',
                                        e.target.value.toLowerCase(),
                                    )
                                }
                            />
                            <p className="text-xs text-muted-foreground">
                                Lowercase letters and numbers, separated by
                                hyphens
                            </p>
                            <InputError message={errors.organization_slug} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="organization_email">
                                Organization Email *
                            </Label>
                            <Input
                                id="organization_email"
                                name="organization_email"
                                type="email"
                                required
                                placeholder="contact@acme.com"
                                value={formData.organization_email || ''}
                                onChange={(e) =>
                                    updateField(
                                        'organization_email',
                                        e.target.value,
                                    )
                                }
                            />
                            <InputError message={errors.organization_email} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="organization_phone">
                                Organization Phone
                            </Label>
                            <Input
                                id="organization_phone"
                                name="organization_phone"
                                type="tel"
                                placeholder="+1 (555) 123-4567"
                                value={formData.organization_phone || ''}
                                onChange={(e) =>
                                    updateField(
                                        'organization_phone',
                                        e.target.value,
                                    )
                                }
                            />
                            <InputError message={errors.organization_phone} />
                        </div>

                        <Button
                            type="button"
                            className="mt-2 w-full"
                            onClick={() => setStep(2)}
                        >
                            Next Step
                        </Button>
                    </div>
                )}

                {/* Step 2: Admin User Information */}
                {step === 2 && (
                    <div className="grid gap-4">
                        <div className="grid gap-2">
                            <Label htmlFor="admin_name">Admin Name *</Label>
                            <Input
                                id="admin_name"
                                name="admin_name"
                                type="text"
                                required
                                autoFocus
                                placeholder="John Doe"
                                value={formData.admin_name || ''}
                                onChange={(e) =>
                                    updateField('admin_name', e.target.value)
                                }
                            />
                            <InputError message={errors.admin_name} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="admin_email">Admin Email *</Label>
                            <Input
                                id="admin_email"
                                name="admin_email"
                                type="email"
                                required
                                placeholder="admin@acme.com"
                                value={formData.admin_email || ''}
                                onChange={(e) =>
                                    updateField('admin_email', e.target.value)
                                }
                            />
                            <InputError message={errors.admin_email} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="admin_password">Password *</Label>
                            <Input
                                id="admin_password"
                                name="admin_password"
                                type="password"
                                required
                                placeholder="••••••••"
                                value={formData.admin_password || ''}
                                onChange={(e) =>
                                    updateField(
                                        'admin_password',
                                        e.target.value,
                                    )
                                }
                            />
                            <InputError message={errors.admin_password} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="admin_password_confirmation">
                                Confirm Password *
                            </Label>
                            <Input
                                id="admin_password_confirmation"
                                name="admin_password_confirmation"
                                type="password"
                                required
                                placeholder="••••••••"
                                value={
                                    formData.admin_password_confirmation || ''
                                }
                                onChange={(e) =>
                                    updateField(
                                        'admin_password_confirmation',
                                        e.target.value,
                                    )
                                }
                            />
                            <InputError
                                message={errors.admin_password_confirmation}
                            />
                        </div>

                        <div className="mt-2 flex gap-2">
                            <Button
                                type="button"
                                variant="outline"
                                className="w-full"
                                onClick={() => setStep(1)}
                            >
                                Back
                            </Button>
                            <Button
                                type="button"
                                className="w-full"
                                onClick={() => setStep(3)}
                            >
                                Next Step
                            </Button>
                        </div>
                    </div>
                )}

                {/* Step 3: Subscription Plan Selection */}
                {step === 3 && (
                    <div className="grid gap-4">
                        <div className="grid gap-4">
                            <Label>Select Your Plan *</Label>
                            <RadioGroup
                                name="subscription_plan"
                                value={formData.subscription_plan || 'trial'}
                                onValueChange={(value) =>
                                    updateField('subscription_plan', value)
                                }
                            >
                                {subscriptionPlans.map((plan) => (
                                    <div
                                        key={plan.value}
                                        className="relative flex items-start space-x-3 rounded-lg border p-4 hover:bg-accent dark:hover:bg-accent/50"
                                    >
                                        <RadioGroupItem
                                            value={plan.value}
                                            id={plan.value}
                                            className="mt-1"
                                        />
                                        <Label
                                            htmlFor={plan.value}
                                            className="flex-1 cursor-pointer"
                                        >
                                            <div className="flex items-center justify-between">
                                                <div>
                                                    <h3 className="font-semibold">
                                                        {plan.name}
                                                    </h3>
                                                    <p className="text-sm text-muted-foreground">
                                                        {plan.description}
                                                    </p>
                                                </div>
                                                <span className="text-sm font-medium">
                                                    {plan.price}
                                                </span>
                                            </div>
                                            <ul className="mt-2 space-y-1 text-xs text-muted-foreground">
                                                {plan.features.map(
                                                    (feature, idx) => (
                                                        <li
                                                            key={idx}
                                                            className="flex items-center"
                                                        >
                                                            <Check className="mr-2 h-3 w-3 text-primary" />
                                                            {feature}
                                                        </li>
                                                    ),
                                                )}
                                            </ul>
                                        </Label>
                                    </div>
                                ))}
                            </RadioGroup>
                            <InputError message={errors.subscription_plan} />
                        </div>

                        <div className="flex items-start space-x-2">
                            <Checkbox
                                id="terms_accepted"
                                name="terms_accepted"
                                checked={
                                    formData.terms_accepted === 'on' ||
                                    formData.terms_accepted === true
                                }
                                onCheckedChange={(checked) =>
                                    updateField(
                                        'terms_accepted',
                                        checked ? 'on' : false,
                                    )
                                }
                                required
                            />
                            <Label
                                htmlFor="terms_accepted"
                                className="cursor-pointer text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            >
                                I accept the{' '}
                                <a
                                    href="#"
                                    className="underline hover:text-primary"
                                >
                                    terms and conditions
                                </a>
                            </Label>
                        </div>
                        <InputError message={errors.terms_accepted} />

                        <div className="mt-2 flex gap-2">
                            <Button
                                type="button"
                                variant="outline"
                                className="w-full"
                                onClick={() => setStep(2)}
                            >
                                Back
                            </Button>
                            <Button
                                type="submit"
                                className="w-full"
                                disabled={processing}
                            >
                                {processing && <Spinner />}
                                Create Organization
                            </Button>
                        </div>
                    </div>
                )}

                <div className="text-center text-sm">
                    Already have an account?{' '}
                    <Link
                        href="/login"
                        className="underline hover:text-primary"
                    >
                        Sign in
                    </Link>
                </div>
            </form>
        </AuthLayout>
    );
}
