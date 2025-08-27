interface EloquentResource<T> extends Response {
    data: T;
    meta?: MetaResource;
    links?: LinkResource;
}

interface LinkResource {
    first?: string;
    last?: string;
    prev?: string;
    next?: string;
}

interface MetaLinkResource {
    url: string;
    label: string;
    active: boolean;
}

interface MetaResource {
    current_page: number;
    from: number;
    last_page: number;
    links: MetaLinkResource[];
    path: string;
    per_page: number;
    to: number;
    total: number;
}
