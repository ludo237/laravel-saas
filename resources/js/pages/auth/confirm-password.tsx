import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth';
import { Form, Head } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { ReactElement } from 'react';

const ConfirmPasswordPage = () => {
    return (
        <>
            <Head title="Confirm password" />

            <Form method="post" action={route('password.confirm')} resetOnSuccess={['password']}>
                {({ processing, errors }) => (
                    <div className="space-y-6">
                        <div className="grid gap-2">
                            <Label htmlFor="password">Password</Label>
                            <Input id="password" type="password" name="password" placeholder="Password" autoComplete="current-password" autoFocus />

                            <InputError message={errors.password} />
                        </div>

                        <div className="flex items-center">
                            <Button className="w-full" disabled={processing}>
                                {processing && <LoaderCircle className="size-4 animate-spin" />}
                                Confirm password
                            </Button>
                        </div>
                    </div>
                )}
            </Form>
        </>
    );
};

ConfirmPasswordPage.layout = (page: ReactElement) => (
    <AuthLayout title="Confirm your password" description="This is a secure area of the application. Please confirm your password before continuing.">
        {page}
    </AuthLayout>
);

export default ConfirmPasswordPage;
