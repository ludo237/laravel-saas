import { Link } from '@/components/link';
import { BreadcrumbItem, BreadcrumbLink } from '@/components/ui/breadcrumb';
import { AppLayout } from '@/layouts/app';
import { Head } from '@inertiajs/react';
import { ReactElement } from 'react';

const DashboardIndex = () => {
    return (
        <>
            <Head title="Dashboard" />

            <div className="flex flex-1 flex-col">Dashboard</div>
        </>
    );
};

DashboardIndex.layout = (page: ReactElement) => (
    <AppLayout.Root>
        <AppLayout.Header>
            <AppLayout.Breadcrumb>
                <BreadcrumbItem>
                    <BreadcrumbLink asChild>
                        <Link href={route('dashboard')}>Dashboard</Link>
                    </BreadcrumbLink>
                </BreadcrumbItem>
            </AppLayout.Breadcrumb>
        </AppLayout.Header>
        <AppLayout.Body>{page}</AppLayout.Body>
    </AppLayout.Root>
);

export default DashboardIndex;
