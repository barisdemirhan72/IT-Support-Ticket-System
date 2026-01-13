

<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('header'); ?>
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
    </h2>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Total Tickets</div>
            <div class="text-3xl font-bold text-gray-900"><?php echo e($totalTickets); ?></div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Open Tickets</div>
            <div class="text-3xl font-bold text-yellow-600"><?php echo e($openTickets); ?></div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">Closed Tickets</div>
            <div class="text-3xl font-bold text-green-600"><?php echo e($closedTickets); ?></div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="text-sm font-medium text-gray-500">New Tickets</div>
            <div class="text-3xl font-bold text-blue-600"><?php echo e($newTickets); ?></div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-900">Recent Tickets</h3>
            <a href="<?php echo e(route('tickets.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create New Ticket
            </a>
        </div>

        <?php if($recentTickets->count() > 0): ?>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php $__currentLoopData = $recentTickets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ticket): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">#<?php echo e($ticket->id); ?></td>
                    <td class="px-6 py-4"><?php echo e(Str::limit($ticket->title, 50)); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo e($ticket->category->name); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                            <?php if($ticket->status === 'new'): ?> bg-blue-100 text-blue-800
                            <?php elseif($ticket->status === 'in_progress'): ?> bg-yellow-100 text-yellow-800
                            <?php elseif($ticket->status === 'completed'): ?> bg-green-100 text-green-800
                            <?php else: ?> bg-gray-100 text-gray-800
                            <?php endif; ?>">
                            <?php echo e(ucwords(str_replace('_', ' ', $ticket->status))); ?>

                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap"><?php echo e($ticket->created_at->format('M d, Y')); ?></td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="<?php echo e(route('tickets.show', $ticket)); ?>" class="text-blue-600 hover:text-blue-900">View</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="text-center py-12">
            <p class="text-gray-500">No tickets yet.</p>
            <a href="<?php echo e(route('tickets.create')); ?>" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Create Your First Ticket
            </a>
        </div>
        <?php endif; ?>

        <?php if($recentTickets->count() > 0): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <a href="<?php echo e(route('tickets.index')); ?>" class="text-blue-600 hover:text-blue-900">View All Tickets →</a>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\BARIŞ\Desktop\OOP\it_support_ticket_system\resources\views/dashboard/user.blade.php ENDPATH**/ ?>