import { Button } from '@/components/ui/button';
import { IconArrowsUpDown } from '@tabler/icons-react';
import type { Column } from '@tanstack/react-table';

const DataTableHeader = <T,>({ text, column }: { text: string; column?: Column<T, unknown> }) => {
    if (column) {
        return (
            <Button variant="ghost" onClick={() => column.toggleSorting(column.getIsSorted() === 'asc')}>
                <span className="text-sm font-medium">{text}</span>
                <IconArrowsUpDown className="ml-2 size-4" />
            </Button>
        );
    }

    return <span className="text-sm font-medium">{text}</span>;
};

export default DataTableHeader;
