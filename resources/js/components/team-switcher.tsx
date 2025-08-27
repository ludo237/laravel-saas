import { Link } from '@/components/link';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuShortcut,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem, useSidebar } from '@/components/ui/sidebar';
import type { Team } from '@/types/models';
import { IconCaretUpDown, IconLetterT, IconPlus, IconUsersGroup } from '@tabler/icons-react';
import { FC, useMemo, useState } from 'react';

const TeamSwitcher: FC<{ teams: Team[] }> = ({ teams }) => {
    const { isMobile } = useSidebar();
    const [activeTeam, setActiveTeam] = useState(teams[0]);
    const avatar = useMemo(() => `https://avatars.laravel.cloud/${activeTeam.id}?vibe=stealth`, [activeTeam]);

    return (
        <SidebarMenu>
            <SidebarMenuItem>
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <SidebarMenuButton size="lg" className="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground">
                            <Avatar className="h-8 w-8 rounded-lg">
                                <AvatarImage src={avatar} alt={activeTeam.name} />
                                <AvatarFallback className="rounded-lg">CN</AvatarFallback>
                            </Avatar>
                            <div className="grid flex-1 text-left text-sm leading-tight">
                                <span className="truncate font-medium">{activeTeam.name}</span>
                                <span className="truncate text-xs">{activeTeam.tier.name}</span>
                            </div>
                            <IconCaretUpDown className="ml-auto" />
                        </SidebarMenuButton>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent
                        className="w-(--radix-dropdown-menu-trigger-width) min-w-56 rounded-lg"
                        align="start"
                        side={isMobile ? 'bottom' : 'right'}
                        sideOffset={4}
                    >
                        <DropdownMenuLabel className="text-xs text-muted-foreground">Teams</DropdownMenuLabel>
                        {teams.map((team, index) => (
                            <DropdownMenuItem key={team.name} onClick={() => setActiveTeam(team)} className="gap-2 p-2">
                                <div className="flex size-6 items-center justify-center rounded-md border">
                                    <IconLetterT className="size-3.5 shrink-0" />
                                </div>
                                {team.name}
                                <DropdownMenuShortcut>⌘{index + 1}</DropdownMenuShortcut>
                            </DropdownMenuItem>
                        ))}
                        <DropdownMenuSeparator />
                        <DropdownMenuItem className="gap-2 p-2">
                            <IconUsersGroup className="size-4" />
                            <Link href={route('team.index')}>Your Teams</Link>
                        </DropdownMenuItem>

                        <DropdownMenuItem className="gap-2 p-2">
                            <IconPlus className="size-4" />
                            <Link href="#" className="cursor-not-allowed font-medium text-muted-foreground">
                                Add team
                            </Link>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            </SidebarMenuItem>
        </SidebarMenu>
    );
};

export default TeamSwitcher;
