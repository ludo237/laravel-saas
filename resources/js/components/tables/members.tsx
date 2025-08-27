import { DataTable } from '@/components/tables/data-table';
import { Button } from '@/components/ui/button';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import type { TeamMember } from '@/types/models';
import { IconArrowsUpDown, IconCheck, IconDotsVertical, IconX } from '@tabler/icons-react';
import { type ColumnDef } from '@tanstack/react-table';
import { formatDistance } from 'date-fns';
import { it } from 'date-fns/locale';

const columns: ColumnDef<TeamMember>[] = [
    {
        accessorKey: 'name',
        header: ({ column }) => {
            return (
                <Button variant="ghost" size="sm" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                    Nome
                    <IconArrowsUpDown className="size-4 pl-2" />
                </Button>
            );
        },
        cell: ({ row }) => {
            return <span className="pl-2.5">{row.getValue('name')}</span>;
        },
    },
    {
        accessorKey: 'email',
        header: 'Email',
        cell: ({ row }) => {
            return <span className="text-foreground">{row.getValue('email')}</span>;
        },
    },
    {
        accessorKey: 'pivot.role.name',
        header: 'Role',
        cell: ({ row }) => {
            const user = row.original;
            const roleName = user.role.name;
            return <span className="text-foreground">{roleName}</span>;
        },
    },
    {
        accessorKey: 'pivot.joinedAt',
        header: 'Joined At',
        cell: ({ row }) => {
            const user = row.original;
            const joinedAt = user.joinedAt;
            return <span className="text-foreground">{formatDistance(joinedAt, new Date(), { addSuffix: true, locale: it })}</span>;
        },
    },
    {
        accessorKey: 'emailVerified',
        header: 'Email Verified',
        cell: ({ row }) => {
            const isVerified = row.getValue<Date | null>('emailVerified');

            return isVerified ? (
                <div className="flex items-center space-x-1.5">
                    <IconCheck className="text-success size-3" />
                    <span className="text-foreground">Yes</span>
                </div>
            ) : (
                <div className="flex items-center space-x-1.5">
                    <IconX className="size-3 text-destructive" />
                    <span className="text-muted-foreground">No</span>
                </div>
            );
        },
    },
    {
        id: 'actions',
        cell: ({ row }) => {
            const user = row.original;
            return (
                <DropdownMenu>
                    <DropdownMenuTrigger asChild>
                        <Button variant="ghost" className="flex size-8 items-center p-0">
                            <span className="sr-only">Open menu</span>
                            <IconDotsVertical className="size-4" />
                        </Button>
                    </DropdownMenuTrigger>
                    <DropdownMenuContent align="end">
                        <DropdownMenuLabel>Options</DropdownMenuLabel>
                        <DropdownMenuItem onClick={() => navigator.clipboard.writeText(user.email)}>Copy email</DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
            );
        },
    },
];

interface MembersTableProps {
    data: TeamMember[];
}

const MembersTable = ({ data }: MembersTableProps) => {
    return (
        <div className="space-y-3">
            <DataTable columns={columns} data={data} />
        </div>
    );
};

export default MembersTable;
