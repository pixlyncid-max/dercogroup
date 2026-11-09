import { CheckCircle2 } from 'lucide-react';
import { useContactForm, useLocale } from '@/core';
import { Button } from './Button';
import { FormField } from './FormField';
import { Input } from './Input';
import { Textarea } from './Textarea';

export interface ContactFormBodyProps {
    submitLabel?: string;
    buttonClassName?: string;
}

export function ContactFormBody({ submitLabel, buttonClassName }: ContactFormBodyProps) {
    const { t } = useLocale();
    const {
        data: fields,
        errors,
        processing,
        isSuccess,
        setField,
        submit,
        resetSuccess,
    } = useContactForm();

    return (
        <div aria-live="polite" className="mt-6">
            {isSuccess ? (
                <div
                    role="status"
                    className="rounded-[var(--radius-card)] border border-accent bg-surface p-6 space-y-4"
                >
                    <div className="flex items-start gap-3">
                        <CheckCircle2
                            className="mt-0.5 h-5 w-5 shrink-0 text-accent-strong"
                            aria-hidden="true"
                        />
                        <div className="space-y-1">
                            <h3 className="font-heading text-base font-bold text-ink">
                                {t('contact.success_title')}
                            </h3>
                            <p className="text-sm leading-6 text-muted">
                                {t('contact.success_body')}
                            </p>
                        </div>
                    </div>
                    <Button variant="outline" size="md" onClick={resetSuccess}>
                        {t('contact.send_another')}
                    </Button>
                </div>
            ) : (
                <form onSubmit={submit} noValidate className="space-y-5">
                    <div className="sr-only" aria-hidden="true">
                        <label htmlFor="contact-website">{t('contact.honeypot_label')}</label>
                        <input
                            id="contact-website"
                            name="website"
                            type="text"
                            tabIndex={-1}
                            autoComplete="off"
                            value={fields.website}
                            onChange={(e) => setField('website', e.target.value)}
                        />
                    </div>

                    <div className="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <FormField
                            id="contact-name"
                            label={t('contact.name_label')}
                            required
                            error={errors.name}
                        >
                            <Input
                                id="contact-name"
                                name="name"
                                type="text"
                                required
                                autoComplete="name"
                                placeholder={t('contact.name_placeholder')}
                                value={fields.name}
                                onChange={(e) => setField('name', e.target.value)}
                                hasError={Boolean(errors.name)}
                                errorId="contact-name-error"
                            />
                        </FormField>

                        <FormField
                            id="contact-email"
                            label={t('contact.email_field_label')}
                            required
                            error={errors.email}
                        >
                            <Input
                                id="contact-email"
                                name="email"
                                type="email"
                                required
                                autoComplete="email"
                                placeholder={t('contact.email_placeholder')}
                                value={fields.email}
                                onChange={(e) => setField('email', e.target.value)}
                                hasError={Boolean(errors.email)}
                                errorId="contact-email-error"
                            />
                        </FormField>
                    </div>

                    <div className="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <FormField
                            id="contact-phone"
                            label={t('contact.phone_field_label')}
                            error={errors.phone}
                        >
                            <Input
                                id="contact-phone"
                                name="phone"
                                type="tel"
                                autoComplete="tel"
                                placeholder={t('contact.phone_placeholder')}
                                value={fields.phone}
                                onChange={(e) => setField('phone', e.target.value)}
                                hasError={Boolean(errors.phone)}
                                errorId="contact-phone-error"
                            />
                        </FormField>

                        <FormField
                            id="contact-organization"
                            label={t('contact.organization_label')}
                            error={errors.organization}
                        >
                            <Input
                                id="contact-organization"
                                name="organization"
                                type="text"
                                autoComplete="organization"
                                placeholder={t('contact.organization_placeholder')}
                                value={fields.organization}
                                onChange={(e) => setField('organization', e.target.value)}
                                hasError={Boolean(errors.organization)}
                                errorId="contact-organization-error"
                            />
                        </FormField>
                    </div>

                    <FormField
                        id="contact-subject"
                        label={t('contact.subject_label')}
                        required
                        error={errors.subject}
                    >
                        <Input
                            id="contact-subject"
                            name="subject"
                            type="text"
                            required
                            placeholder={t('contact.subject_placeholder')}
                            value={fields.subject}
                            onChange={(e) => setField('subject', e.target.value)}
                            hasError={Boolean(errors.subject)}
                            errorId="contact-subject-error"
                        />
                    </FormField>

                    <FormField
                        id="contact-message"
                        label={t('contact.message_label')}
                        required
                        error={errors.message}
                    >
                        <Textarea
                            id="contact-message"
                            name="message"
                            required
                            placeholder={t('contact.message_placeholder')}
                            value={fields.message}
                            onChange={(e) => setField('message', e.target.value)}
                            hasError={Boolean(errors.message)}
                            errorId="contact-message-error"
                        />
                    </FormField>

                    <div className="pt-2">
                        <Button
                            type="submit"
                            variant="solid"
                            size="lg"
                            disabled={processing}
                            className={
                                buttonClassName ?? 'text-xs font-bold uppercase tracking-[0.12em]'
                            }
                        >
                            {processing
                                ? t('contact.submitting')
                                : submitLabel || t('send_message')}
                        </Button>
                    </div>
                </form>
            )}
        </div>
    );
}
