import { type FormEvent, useState } from 'react';
import { useForm, usePage } from '@inertiajs/react';
import type { SharedPageProps } from '@/core/types';

export interface ContactFormFields {
    name: string;
    email: string;
    phone: string;
    organization: string;
    subject: string;
    message: string;
    website: string;
}

export interface UseContactFormResult {
    data: ContactFormFields;
    errors: Partial<Record<keyof ContactFormFields, string>>;
    processing: boolean;
    isSuccess: boolean;
    setField: (field: keyof ContactFormFields, value: string) => void;
    submit: (event: FormEvent<HTMLFormElement>) => void;
    resetSuccess: () => void;
}

export function useContactForm(): UseContactFormResult {
    const { props } = usePage<SharedPageProps>();
    const locale = props.locale ?? 'id';
    const submitUrl = props.contactSubmitUrl ?? `/${locale}/contact-messages`;
    const flashSuccess = Boolean(props.flash?.contactSuccess);

    const [submittedSuccess, setSubmittedSuccess] = useState(false);

    const form = useForm<ContactFormFields>({
        name: '',
        email: '',
        phone: '',
        organization: '',
        subject: '',
        message: '',
        website: '',
    });

    const setField = (field: keyof ContactFormFields, value: string) => {
        form.setData(field, value);
    };

    const submit = (event: FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        form.post(submitUrl, {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                setSubmittedSuccess(true);
            },
        });
    };

    return {
        data: form.data,
        errors: form.errors,
        processing: form.processing,
        isSuccess: submittedSuccess || flashSuccess,
        setField,
        submit,
        resetSuccess: () => setSubmittedSuccess(false),
    };
}
