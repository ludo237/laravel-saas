export interface IModel {
    readonly id: string;
    readonly createdAt: Date;
    readonly updatedAt: Date;
}

export interface SoftDeleteModel {
    deletedAt: Date | null;
}

export interface User extends IModel, SoftDeleteModel {
    shortName: string;
    name: string;
    email: string;
    emailVerified: Date | null;
    phone: string;
    phoneVerified: Date | null;
    memberOf: Team[];
    pivot?: {
        role?: TeamRole;
        joinedAt?: Date;
    };
}

export interface Tier extends IModel {
    name: string;
}

export interface TeamTier extends Omit<Tier> {
    name: string;
    [key: string]: unknown;
}

export interface TeamRole extends IModel {
    slug: string;
    name: string;
    permissions: Record<string, string[]>;
}

export interface TeamMember extends User, IModel {
    role: TeamRole;
    joinedAt: string;
}

export interface Team extends IModel, SoftDeleteModel {
    name: string;
    tier: TeamTier;
    members: TeamMember[];
    counts: {
        members: number;
    };
}
