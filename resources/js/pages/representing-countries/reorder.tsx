import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import {
    type BreadcrumbItem,
    type RepresentingCountry,
    type RepCountryStatus,
} from '@/types';
import { Head, Link, router } from '@inertiajs/react';
import { GripVertical, Loader2, ArrowLeft, Info, CheckCircle, ArrowUpDown } from 'lucide-react';
import { dashboard } from '@/routes';
import * as representingCountries from '@/routes/representing-countries';
import { useState, useRef, useEffect } from 'react';
import {
    DndContext,
    closestCenter,
    KeyboardSensor,
    PointerSensor,
    useSensor,
    useSensors,
    DragEndEvent,
} from '@dnd-kit/core';
import {
    arrayMove,
    SortableContext,
    sortableKeyboardCoordinates,
    useSortable,
    verticalListSortingStrategy,
} from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';

interface Props {
    representingCountry: RepresentingCountry;
}

interface SortableItemProps {
    status: RepCountryStatus;
    index: number;
}

function SortableItem({ status, index }: SortableItemProps) {
    const isNewStatus = status.status_name === 'New';

    const {
        attributes,
        listeners,
        setNodeRef,
        transform,
        transition,
        isDragging,
    } = useSortable({
        id: status.id.toString(),
        disabled: isNewStatus,
    });

    const style = {
        transform: CSS.Transform.toString(transform),
        transition,
        opacity: isDragging ? 0.5 : 1,
        cursor: isNewStatus ? 'not-allowed' : 'move',
    };

    return (
        <div
            ref={setNodeRef}
            style={style}
            className={`flex items-center gap-3 rounded-lg border bg-card p-4 shadow-sm transition-all duration-200 ${
                isNewStatus
                    ? 'opacity-60 border-gray-200'
                    : 'hover:shadow-md hover:border-blue-300'
            } ${isDragging ? 'shadow-lg scale-105' : ''}`}
        >
            {/* Drag Handle or Indicator */}
            {isNewStatus ? (
                <div className="w-5 h-5 flex items-center justify-center">
                    <div className="w-1.5 h-1.5 rounded-full bg-muted-foreground"></div>
                </div>
            ) : (
                <button
                    className="cursor-grab touch-none active:cursor-grabbing p-1 rounded hover:bg-gray-100"
                    {...attributes}
                    {...listeners}
                >
                    <GripVertical className="h-5 w-5 text-muted-foreground" />
                </button>
            )}

            {/* Step Number */}
            <div className={`flex h-8 w-8 items-center justify-center rounded-full text-sm font-semibold ${
                isNewStatus
                    ? 'bg-gray-200 text-gray-500'
                    : 'bg-primary/10 text-primary'
            }`}>
                {index + 1}
            </div>

            {/* Content */}
            <div className="flex-1">
                <p className={`font-medium ${isNewStatus ? 'text-gray-500' : 'text-gray-900'}`}>
                    {status.custom_name || status.status_name}
                </p>
                {isNewStatus && (
                    <p className="text-xs text-muted-foreground mt-0.5">
                        System Status - Cannot be reordered
                    </p>
                )}
            </div>
        </div>
    );
}

export default function Reorder({ representingCountry }: Props) {
    const [statuses, setStatuses] = useState<RepCountryStatus[]>(
        representingCountry.rep_country_statuses || []
    );
    const [isSaving, setIsSaving] = useState(false);
    const prevOrderRef = useRef<string[]>(statuses.map(s => s.id.toString()));

    useEffect(() => {
        prevOrderRef.current = statuses.map(s => s.id.toString());
    }, [statuses]);

    const sensors = useSensors(
        useSensor(PointerSensor),
        useSensor(KeyboardSensor, {
            coordinateGetter: sortableKeyboardCoordinates,
        })
    );

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: dashboard().url,
        },
        {
            title: 'Representing Countries',
            href: representingCountries.index().url,
        },
        {
            title: representingCountry.country.name,
            href: representingCountries.index().url,
        },
    ];

    const handleDragEnd = (event: DragEndEvent) => {
        const { active, over } = event;

        if (over && active.id !== over.id) {
            const oldIndex = statuses.findIndex(
                (item) => item.id.toString() === active.id
            );
            const newIndex = statuses.findIndex(
                (item) => item.id.toString() === over.id
            );

            // Prevent dragging to or from the "New" status position
            const newStatusIndex = statuses.findIndex(s => s.status_name === 'New');
            if (oldIndex === newStatusIndex || newIndex === newStatusIndex) {
                return;
            }

            const newItems = arrayMove(statuses, oldIndex, newIndex);

            setStatuses(newItems);

            const statusOrders = newItems.map((s, index) => ({
                id: s.id,
                order: index + 1,
            }));

            setIsSaving(true);
            router.post(
                representingCountries.updateOrder(representingCountry.id).url,
                {
                    status_orders: statusOrders,
                },
                {
                    preserveScroll: true,
                    onFinish: () => {
                        setIsSaving(false);
                    },
                }
            );
        }
    };

    const movableSteps = statuses.filter(s => s.status_name !== 'New').length;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head
                title={`Reorder Statuses - ${representingCountry.country.name}`}
            />

            <div className="flex h-full flex-1 flex-col gap-6 p-4">
                {/* Header Section */}
                <div className="flex items-center justify-between">
                    <div>
                        <h1 className="text-3xl font-bold">
                            Reorder Application Statuses
                        </h1>
                        <p className="mt-2 text-muted-foreground">
                            Drag and drop to reorder the application statuses
                            for{' '}
                            <span className="font-medium">
                                {representingCountry.country.flag}{' '}
                                {representingCountry.country.name}
                            </span>
                        </p>
                    </div>
                    <div className="flex items-center gap-3">
                        {isSaving && (
                            <div className="flex items-center gap-2 text-sm text-muted-foreground">
                                <Loader2 className="h-4 w-4 animate-spin" />
                                <span>Saving changes...</span>
                            </div>
                        )}
                        <Link href={representingCountries.index().url}>
                            <Button variant="outline">
                                <ArrowLeft className="mr-2 h-4 w-4" />
                                Back to Countries
                            </Button>
                        </Link>
                    </div>
                </div>

                {/* Instructions Card */}
                <Card>
                    <CardContent className="p-4">
                        <div className="flex items-start gap-3">
                            <div className="p-2 bg-blue-100 rounded-lg flex-shrink-0">
                                <Info className="w-4 h-4 text-blue-600" />
                            </div>
                            <div className="flex-1">
                                <h3 className="font-medium mb-2 text-sm">How to reorder steps</h3>
                                <ul className="text-xs text-muted-foreground space-y-1">
                                    <li>• Drag and drop the steps below to change their order</li>
                                    <li>• The "New" step is fixed at the beginning and cannot be moved</li>
                                    <li>• Changes are saved automatically when you reorder</li>
                                    <li>• The order determines the sequence of the application process</li>
                                </ul>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Reorder Section */}
                <Card>
                    <CardHeader>
                        <div className="flex items-center justify-between">
                            <div>
                                <CardTitle>Application Status Steps</CardTitle>
                                <CardDescription>
                                    {movableSteps} steps can be reordered • {statuses.length - movableSteps} step is fixed
                                </CardDescription>
                            </div>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <DndContext
                            sensors={sensors}
                            collisionDetection={closestCenter}
                            onDragEnd={handleDragEnd}
                        >
                            <SortableContext
                                items={statuses.map((s) => s.id.toString())}
                                strategy={verticalListSortingStrategy}
                            >
                                <div className="space-y-3">
                                    {statuses.map((status, index) => (
                                        <SortableItem
                                            key={status.id}
                                            status={status}
                                            index={index}
                                        />
                                    ))}
                                </div>
                            </SortableContext>
                        </DndContext>
                    </CardContent>
                </Card>

                {/* Summary Card */}
                <Card>
                    <CardContent className="p-4">
                        <div className="flex items-start gap-3">
                            <div className="p-2 bg-green-100 rounded-lg flex-shrink-0">
                                <CheckCircle className="w-4 h-4 text-green-600" />
                            </div>
                            <div className="flex-1">
                                <h3 className="font-medium text-sm mb-1">Current Order</h3>
                                <p className="text-xs text-muted-foreground">
                                    {statuses.map((s) => s.custom_name || s.status_name).join(' → ')}
                                </p>
                                <Badge variant="secondary" className="mt-2">
                                    {statuses.length} Total Steps
                                </Badge>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
