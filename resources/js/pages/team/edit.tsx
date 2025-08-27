import InviteMemberForm from '@/components/forms/invite-member';
import TeamForm from '@/components/forms/team';
import { Link } from '@/components/link';
import MembersTable from '@/components/tables/members';
import { BreadcrumbItem, BreadcrumbLink, BreadcrumbSeparator } from '@/components/ui/breadcrumb';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import { AppLayout } from '@/layouts/app';
import { SharedPageProps } from '@/types';
import { Team, TeamRole } from '@/types/models';
import { Head } from '@inertiajs/react';
import { IconUsersGroup } from '@tabler/icons-react';
import { ReactElement } from 'react';

interface PageProps extends SharedPageProps {
    team: EloquentResource<Team>;
    roles: EloquentResource<TeamRole[]>;
}

const TeamEditPage = ({ team, roles }: PageProps) => {
    return (
        <>
            <Head title="Il team" />

            <div className="grid grid-cols-1 gap-x-6 md:grid-cols-12">
                <div className="flex grow flex-col space-y-6 md:col-span-8">
                    <Card>
                        <CardHeader>Edit team data</CardHeader>
                        <CardContent>
                            <TeamForm team={team.data} />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <div className="flex items-center justify-between">
                                <div className="grow">Members</div>

                                <div>
                                    <InviteMemberForm team={team.data} roles={roles.data} />
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent>
                            {team.data.members && team.data.members.length > 1 ? (
                                <MembersTable data={team.data.members} />
                            ) : (
                                <div className="py-8 text-center text-muted-foreground">
                                    <IconUsersGroup className="mx-auto mb-4 size-12" />
                                    <p className="text-lg font-medium">This team has no member besides you</p>
                                    <p className="text-sm">Start by inviting new members in {team.data.name}.</p>
                                </div>
                            )}
                        </CardContent>
                    </Card>
                </div>

                <Card className="self-start md:col-span-4">
                    <CardHeader>Statistics</CardHeader>
                    <CardContent>
                        <ul className="flex flex-col space-y-6">
                            <li className="flex items-center space-x-1.5">
                                <IconUsersGroup className="size-6" />
                                <span>
                                    {team.data.counts.members} {team.data.counts.members === 1 ? 'Member' : 'Members'}
                                </span>
                            </li>
                        </ul>
                    </CardContent>
                </Card>
            </div>
        </>
    );
};

TeamEditPage.layout = (page: ReactElement<PageProps>) => (
    <AppLayout.Root>
        <AppLayout.Header>
            <AppLayout.Breadcrumb>
                <BreadcrumbLink asChild>
                    <Link href={route('team.index')}>Teams</Link>
                </BreadcrumbLink>
                <BreadcrumbSeparator />
                <BreadcrumbItem>{page.props.team.data.id}</BreadcrumbItem>
            </AppLayout.Breadcrumb>
        </AppLayout.Header>
        <AppLayout.Body>{page}</AppLayout.Body>
    </AppLayout.Root>
);

export default TeamEditPage;
