import { AppPageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';

export function usePermission() {
    const hasPermission = (name: string) => {
        const page = usePage<AppPageProps>();
        const permissions = page.props.auth?.user?.permissions || [];

        return permissions.includes(name);
    };

    const hasRoles = (roles: string[]) => {
        const page = usePage<AppPageProps>();
        const userRoles = page.props.auth?.user?.roles || [];

        return roles.some((role) => userRoles.includes(role));
    };

    return { hasPermission, hasRoles };
}
