import {
    Anchor,
    Award,
    Building2,
    CheckCircle2,
    Clock,
    Compass,
    Factory,
    FileCheck,
    Layers,
    Mail,
    MapPin,
    Mountain,
    Phone,
    Scale,
    Shield,
    ShieldCheck,
    Ship,
    Truck,
    type LucideProps,
} from 'lucide-react';
import type { ComponentType } from 'react';

const iconMap: Record<string, ComponentType<LucideProps>> = {
    anchor: Anchor,
    award: Award,
    building: Building2,
    'check-circle-2': CheckCircle2,
    clock: Clock,
    compass: Compass,
    factory: Factory,
    'file-check': FileCheck,
    layers: Layers,
    mail: Mail,
    'map-pin': MapPin,
    mountain: Mountain,
    phone: Phone,
    scale: Scale,
    shield: Shield,
    'shield-check': ShieldCheck,
    ship: Ship,
    truck: Truck,
};

export interface ThemeIconProps extends LucideProps {
    name?: string;
}

export function ThemeIcon({ name = 'shield-check', strokeWidth = 1.75, ...rest }: ThemeIconProps) {
    const IconComponent = iconMap[name] ?? ShieldCheck;

    return <IconComponent strokeWidth={strokeWidth} aria-hidden="true" {...rest} />;
}
