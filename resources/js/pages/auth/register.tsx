import { Head, router } from '@inertiajs/react';
import { useEffect } from 'react';

export default function Register() {
    // Redirect to organization registration (B2B SaaS - organizations register, not individual users)
    useEffect(() => {
        router.visit('/register');
    }, []);

    return (
        <>
            <Head title="Register" />
            <div className="flex min-h-screen items-center justify-center">
                <div className="text-center">
                    <h1 className="text-lg font-medium">
                        Redirecting to organization registration...
                    </h1>
                </div>
            </div>
        </>
    );
}
