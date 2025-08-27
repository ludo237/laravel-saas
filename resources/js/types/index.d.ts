import { Team, User } from '@/types/models';
import { PageProps } from '@inertiajs/core';
import type { Config } from 'ziggy-js';

export interface SharedPageProps extends PageProps {
    csrf_token: string;
    auth: {
        user: EloquentResource<User> | null;
        teams: EloquentResource<Team[]> | null;
    };
    flash: {
        message: string | null;
        error: string | null;
    };
    sidebarOpen: boolean;
    ziggy: Config & { location: string };
    [key: string]: unknown;
}
