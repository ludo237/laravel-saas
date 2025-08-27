import { cn } from '@/lib/utils';
import { Link as InertiaLink, type InertiaLinkProps } from '@inertiajs/react';
import { cva, type VariantProps } from 'class-variance-authority';
import { FC, PropsWithChildren } from 'react';

const linkVariants = cva(
    'text-sm font-medium transition-colors disabled:pointer-events-none hover:cursor-pointer disabled:cursor-not-allowed disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0',
    {
        variants: {
            variant: {
                default: 'text-primary hover:underline hover:text-primary ',
                destructive: 'bg-destructive text-destructive-foreground hover:underline hover:text-destructive',
                button: 'text-inherit hover:text-inherit',
            },
        },
        defaultVariants: {
            variant: 'default',
        },
    },
);

export interface LinkProps extends InertiaLinkProps, PropsWithChildren, VariantProps<typeof linkVariants> {
    className?: string;
    external?: boolean;
    download?: boolean;
}

const Link: FC<LinkProps> = (props) => {
    const { className, variant, external = false, href, children, download = false, onProgress, ...rest } = props;

    if (external || download) {
        return (
            <a className={cn(linkVariants({ variant, className }))} href={href?.toString()} target="_blank" download={download}>
                {children}
            </a>
        );
    }

    return (
        <InertiaLink onProgress={onProgress} className={cn(linkVariants({ variant, className }))} href={href} {...rest}>
            {children}
        </InertiaLink>
    );
};

export { Link, linkVariants };
