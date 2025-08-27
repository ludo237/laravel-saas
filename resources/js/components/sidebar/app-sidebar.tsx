import SidebarMainNav from '@/components/sidebar/main-nav';
import NavUser from '@/components/sidebar/nav-user';
import TeamSwitcher from '@/components/team-switcher';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader } from '@/components/ui/sidebar';
import { SharedPageProps } from '@/types';
import { usePage } from '@inertiajs/react';
import { ComponentProps, FC } from 'react';

const AppSidebar: FC<ComponentProps<typeof Sidebar>> = ({ ...props }) => {
    const {
        auth: { user, teams },
    } = usePage<SharedPageProps>().props;

    return (
        <Sidebar collapsible="offcanvas" {...props}>
            <SidebarHeader>
                <TeamSwitcher teams={teams?.data!} />
            </SidebarHeader>
            <SidebarContent>
                <SidebarMainNav />
            </SidebarContent>
            <SidebarFooter>
                <NavUser user={user!.data} />
            </SidebarFooter>
        </Sidebar>
    );
};

export default AppSidebar;
