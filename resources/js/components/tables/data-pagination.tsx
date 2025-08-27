import { PaginationContent, PaginationEllipsis, PaginationItem, PaginationLink, Pagination as RootPagination } from '@/components/ui/pagination';

type DataPaginationProps = {
    links: LinkResource;
    meta: MetaResource;
    onPageChange: (url: string) => void;
};

const DataPagination = (props: DataPaginationProps) => {
    const { meta, onPageChange } = props;

    return (
        <RootPagination>
            <PaginationContent>
                {meta.links.map((i) => (
                    <PaginationItem key={i.label}>
                        {i.label === '...' ? (
                            <PaginationEllipsis />
                        ) : (
                            <PaginationLink isActive={i.active} onClick={() => i.url && onPageChange(i.url)} className="cursor-pointer" size="sm">
                                {i.label}
                            </PaginationLink>
                        )}
                    </PaginationItem>
                ))}
            </PaginationContent>
        </RootPagination>
    );
};

export default DataPagination;
