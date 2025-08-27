import AppSidebar from '@/components/sidebar/app-sidebar';
import { BreadcrumbList, Breadcrumb as BreadcrumbUI } from '@/components/ui/breadcrumb';
import { Separator } from '@/components/ui/separator';
import { SidebarInset, SidebarProvider, SidebarTrigger } from '@/components/ui/sidebar';
import { Toaster } from '@/components/ui/sonner';
import { SharedPageProps } from '@/types';
import { usePage } from '@inertiajs/react';
import { CSSProperties, FC, ReactElement, ReactNode, useEffect } from 'react';
import { toast } from 'sonner';

const Root: FC<{ children: ReactNode }> = ({ children }) => {
    const { props } = usePage<SharedPageProps>();

    // Handle flash messages
    useEffect(() => {
        if (props.flash?.error) {
            toast.error(props.flash.error);
        }
        if (props.flash?.message) {
            toast.success(props.flash.message);
        }
    }, [props.flash]);

    return (
        <>
            <SidebarProvider
                style={
                    {
                        '--sidebar-width': 'calc(var(--spacing) * 72)',
                        '--header-height': 'calc(var(--spacing) * 12)',
                    } as CSSProperties
                }
            >
                <AppSidebar variant="inset" />
                <SidebarInset>{children}</SidebarInset>
            </SidebarProvider>

            <Toaster richColors closeButton />
        </>
    );
};

const Header: FC<{ children: ReactNode }> = ({ children }) => {
    return (
        <header className="flex h-(--header-height) shrink-0 items-center gap-2 border-b transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-(--header-height)">
            <div className="flex w-full items-center gap-1 px-4 lg:gap-2 lg:px-6">
                <SidebarTrigger className="-ml-1" />
                <Separator orientation="vertical" className="mx-2 data-[orientation=vertical]:h-4" />
                {children}
            </div>
        </header>
    );
};

const Actions: FC<{ children: ReactNode }> = ({ children }) => {
    return <div className="ml-auto flex items-center gap-2">{children}</div>;
};

const Breadcrumb: FC<{ children: ReactNode }> = ({ children }) => {
    return (
        <BreadcrumbUI>
            <BreadcrumbList>{children}</BreadcrumbList>
        </BreadcrumbUI>
    );
};

const Body: FC<{ children: ReactElement }> = ({ children }) => {
    return <div className="w-full p-6">{children}</div>;
};

export const AppLayout = {
    Root,
    Header,
    Breadcrumb,
    Actions,
    Body,
};
