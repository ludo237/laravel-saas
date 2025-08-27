import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Team } from '@/types/models';
import { useForm } from '@inertiajs/react';
import { type FC, FormEventHandler } from 'react';

type TeamFormSchema = {
    name: string;
};

const TeamForm: FC<{ team: Team }> = ({ team }) => {
    const { data, setData, put, processing, errors } = useForm<TeamFormSchema>({
        name: team.name,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('team.update', team.id));
    };

    return (
        <form onSubmit={submit} className="space-y-6">
            <div className="grid gap-3">
                <Label htmlFor="name">Name</Label>
                <Input
                    id="name"
                    placeholder="Fantastic Four"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    disabled={processing}
                    required
                />
                <div className="text-sm text-muted-foreground">Team name, max 20 chars</div>
                {errors.name && <div className="text-sm text-destructive">{errors.name}</div>}
            </div>

            <div className="flex w-full justify-end">
                <Button type="submit" disabled={processing}>
                    {processing ? 'Updating...' : 'Update'}
                </Button>
            </div>
        </form>
    );
};

export default TeamForm;
