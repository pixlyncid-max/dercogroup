import {
    Award,
    Building2,
    CheckCircle2,
    Clock,
    Compass,
    FileCheck,
    Mail,
    MapPin,
    Phone,
    Scale,
    Shield,
    ShieldCheck,
    type LucideProps,
} from 'lucide-react';

const iconMap: Record<string, React.ComponentType<LucideProps>> = {
    award: Award,
    building: Building2,
    'check-circle-2': CheckCircle2,
    clock: Clock,
    compass: Compass,
    'file-check': FileCheck,
    mail: Mail,
    'map-pin': MapPin,
    phone: Phone,
    scale: Scale,
    shield: Shield,
    'shield-check': ShieldCheck,
};

export interface HoldingIconProps extends LucideProps {
    name?: string;
}

export function HoldingIcon({
    name = 'shield-check',
    strokeWidth = 1.75,
    ...rest
}: HoldingIconProps) {
    const IconComponent = iconMap[name] ?? ShieldCheck;

    return <IconComponent strokeWidth={strokeWidth} aria-hidden="true" {...rest} />;
}
