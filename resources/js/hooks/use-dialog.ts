import { useState } from 'react';

export function useDialog<T = undefined>() {
    const [isOpen, setIsOpen] = useState(false);
    const [data, setData] = useState<T | undefined>(undefined);

    const open = (dialogData?: T) => {
        if (dialogData !== undefined) {
            setData(dialogData);
        }
        setIsOpen(true);
    };

    const close = () => {
        setIsOpen(false);
        setData(undefined);
    };

    return {
        isOpen,
        data,
        open,
        close,
        setData,
    };
}
