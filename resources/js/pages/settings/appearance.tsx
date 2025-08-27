import AppearanceTabs from '@/components/appearance-tabs';
import HeadingSmall from '@/components/heading-small';
import { Link } from '@/components/link';
import { BreadcrumbItem, BreadcrumbLink, BreadcrumbSeparator } from '@/components/ui/breadcrumb';
import { AppLayout } from '@/layouts/app';
import SettingsLayout from '@/layouts/settings/layout';
import { Head } from '@inertiajs/react';
import { ReactElement } from 'react';

const AppearancePage = () => {
    return (
        <>
            <Head title="Appearance settings" />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall title="Appearance settings" description="Update your account's appearance settings" />
                    <AppearanceTabs />
                </div>
            </SettingsLayout>
        </>
    );
};

AppearancePage.layout = (page: ReactElement) => (
    <AppLayout.Root>
        <AppLayout.Header>
            <AppLayout.Breadcrumb>
                <BreadcrumbItem>
                    <BreadcrumbLink asChild>
                        <Link href={route('profile.edit')}>Profile</Link>
                    </BreadcrumbLink>
                </BreadcrumbItem>
                <BreadcrumbSeparator />
                <BreadcrumbItem>Appearance</BreadcrumbItem>
            </AppLayout.Breadcrumb>
        </AppLayout.Header>
        <AppLayout.Body>{page}</AppLayout.Body>
    </AppLayout.Root>
);

export default AppearancePage;
