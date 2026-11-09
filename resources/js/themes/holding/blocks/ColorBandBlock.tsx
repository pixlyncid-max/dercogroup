import type { ColorBandBlockData } from '@/core';
import { ColorBand } from '../ColorBand';

export interface ColorBandBlockProps {
    data: ColorBandBlockData;
}

export function ColorBandBlock({ data }: ColorBandBlockProps) {
    return <ColorBand data={data} />;
}
