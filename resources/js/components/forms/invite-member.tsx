import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Team, TeamRole } from '@/types/models';
import { useForm } from '@inertiajs/react';
import { IconUserPlus } from '@tabler/icons-react';
import { FC, FormEventHandler, useState } from 'react';

type InviteMemberFormSchema = {
    team: string;
    email: string;
    role: string;
};

interface InviteMemberFormProps {
    roles: TeamRole[];
    team: Team;
}

const InviteMemberForm: FC<InviteMemberFormProps> = ({ team, roles }) => {
    const [isOpen, setIsOpen] = useState<boolean>(false);
    const { errors, data, setData, post, processing, reset } = useForm<InviteMemberFormSchema>({
        team: team.id,
        email: '',
        role: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('team.invite.create', team.id), {
            onSuccess: () => {
                reset();
                setIsOpen(false);
            },
        });
    };

    return (
        <Dialog open={isOpen}>
            <DialogTrigger onClick={() => setIsOpen(true)}>
                <div className="flex items-center space-x-1.5">
                    <IconUserPlus className="size-3" />
                    <span>Invite</span>
                </div>
            </DialogTrigger>
            <DialogContent showCloseButton={false}>
                <DialogHeader>
                    <DialogTitle>Invite someone in {team.name}</DialogTitle>
                    <DialogDescription>
                        Insert an email, give he/she a role. User will receive an invite to register, or to login, and it will be automatically added
                        to the team.
                    </DialogDescription>
                </DialogHeader>
                <form onSubmit={submit}>
                    <div className="space-y-6">
                        <div className="space-y-3">
                            <Label htmlFor="email">Email</Label>
                            <Input
                                id="email"
                                name="email"
                                type="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                disabled={processing}
                                placeholder="jhon.doe@example.com"
                                required
                            />

                            {errors.email && <div className="text-sm text-destructive">{errors.email}</div>}
                        </div>
                        <div className="space-y-3">
                            <Label htmlFor="role">Role</Label>
                            <Select name="role" onValueChange={(e) => setData('role', e)} defaultValue={data.role} required disabled={processing}>
                                <SelectTrigger className="w-full">
                                    <SelectValue placeholder="Select a role" />
                                </SelectTrigger>
                                <SelectContent>
                                    {roles.map((r) => (
                                        <SelectItem key={r.id} value={r.id} className="capitalize">
                                            {r.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>

                            {errors.role && <div className="text-sm text-destructive">{errors.role}</div>}
                        </div>
                    </div>
                    <DialogFooter>
                        <DialogClose asChild>
                            <Button variant="outline" onClick={() => setIsOpen(false)}>
                                Cancel
                            </Button>
                        </DialogClose>
                        <Button type="submit" disabled={processing}>
                            {processing ? 'Sending...' : 'Send'}
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    );
};

export default InviteMemberForm;
