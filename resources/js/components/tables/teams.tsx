import { Link } from '@/components/link';
import DataPagination from '@/components/tables/data-pagination';
import { DataTable, TableProps } from '@/components/tables/data-table';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { HoverCard, HoverCardContent, HoverCardTrigger } from '@/components/ui/hover-card';
import type { Team } from '@/types/models';
import { router } from '@inertiajs/react';
import { IconArrowsUpDown, IconDotsVertical, IconUsersGroup } from '@tabler/icons-react';
import { type ColumnDef } from '@tanstack/react-table';
import { formatDistance } from 'date-fns';
import { it } from 'date-fns/locale';
import { FC, ReactElement, useEffect, useState } from 'react';

interface TeamsTableStatsDetailProps {
    trigger: ReactElement;
    content: ReactElement;
}

const TeamsTableStatsDetail: FC<TeamsTableStatsDetailProps> = ({ trigger, content }) => {
    return (
        <HoverCard>
            <HoverCardTrigger>{trigger}</HoverCardTrigger>
            <HoverCardContent>{content}</HoverCardContent>
        </HoverCard>
    );
};

const columns: ColumnDef<Team>[] = [
    {
        accessorKey: 'name',
        header: ({ column }) => {
            return (
                <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                    Name
                    <IconArrowsUpDown className="size-4 pl-2" />
                </Button>
            );
        },
        cell: ({ row }) => {
            return <span className="pl-2.5">{row.getValue('name')}</span>;
        },
    },
    {
        accessorKey: 'tier',
        header: 'Tier',
        cell: ({ row }) => row.original.tier.name,
    },
    {
        header: 'Statistics',
        cell: ({ row }) => {
            return (
                <div className="inline-flex items-center space-x-3">
                    <TeamsTableStatsDetail
                        trigger={
                            <div className="inline-flex items-center space-x-0.5">
                                <span>{row.original.counts.members}</span>
                                <IconUsersGroup className="size-4" />
                            </div>
                        }
                        content={<>Number of users being member of this team</>}
                    />
                </div>
            );
        },
    },
    {
        accessorKey: 'createdAt',
        header: 'Created',
        cell: ({ row }) => {
            const date = row.getValue<Date>('createdAt');
            return <span className="text-secondary-foreground">{formatDistance(date, new Date(), { addSuffix: true, locale: it })}</span>;
        },
    },
    {
        accessorKey: 'updatedAt',
        header: 'Last Update',
        cell: ({ row }) => {
            const date = row.getValue<Date>('updatedAt');
            return <span className="text-secondary-foreground">{formatDistance(date, new Date(), { addSuffix: true, locale: it })}</span>;
        },
    },
    {
        id: 'actions',
        cell: ({ row }) => {
            const team = row.original;
            return (
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="ghost" className="flex size-8 items-center p-0">
                            <span className="sr-only">Open menu</span>
                            <IconDotsVertical className="size-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>Option</DropdownMenuLabel>
                        <DropdownMenuItem asChild>
                            <Link href={route('team.show', team.id)}>Details</Link>
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            );
        },
    },
];

const TeamsTable = ({ data, links, meta }: TableProps<Team>) => {
    const [items, setItems] = useState<Team[]>(data);

    const handlePageChange = (url: string) => {
        const pageUrl = new URL(url);
        const page = pageUrl.searchParams.get('page');
        router.visit(`/teams?page=${page}`);
    };

    useEffect(() => {
        setItems(data);
    }, [data]);

    return (
        <div className="space-y-3">
            <DataTable columns={columns} data={items} />
            {meta && links && <DataPagination links={links} meta={meta} onPageChange={handlePageChange} />}
        </div>
    );
};

export default TeamsTable;
