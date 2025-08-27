import TeamsTable from '@/components/tables/teams';
import { BreadcrumbItem } from '@/components/ui/breadcrumb';
import { AppLayout } from '@/layouts/app';
import { SharedPageProps } from '@/types';
import { Team } from '@/types/models';
import { Head } from '@inertiajs/react';
import { ReactElement } from 'react';

interface PageProps extends SharedPageProps {
    teams: EloquentResource<Team[]>;
}

const TeamIndexPage = ({ teams }: PageProps) => {
    return (
        <>
            <Head title="I tuoi team" />

            <TeamsTable data={teams.data} meta={teams.meta} links={teams.links} />
        </>
    );
};

TeamIndexPage.layout = (page: ReactElement<PageProps>) => (
    <AppLayout.Root>
        <AppLayout.Header>
            <AppLayout.Breadcrumb>
                <BreadcrumbItem>Teams</BreadcrumbItem>
            </AppLayout.Breadcrumb>
        </AppLayout.Header>
        <AppLayout.Body>{page}</AppLayout.Body>
    </AppLayout.Root>
);

export default TeamIndexPage;
