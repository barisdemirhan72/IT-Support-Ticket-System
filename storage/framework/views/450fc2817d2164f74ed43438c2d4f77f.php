

<?php $__env->startSection('title', 'Ticket #' . $ticket->id); ?>

<?php $__env->startSection('header'); ?>
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Ticket #<?php echo e($ticket->id); ?>

        </h2>
        <div class="flex space-x-2">
            <?php if(auth()->user()->id === $ticket->user_id && $ticket->isNew()): ?>
                <a href="<?php echo e(route('tickets.edit', $ticket)); ?>" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                    Edit Ticket
                </a>
            <?php endif; ?>
            <a href="<?php echo e(route('tickets.index')); ?>" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700">
                Back to List
            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Ticket Details -->
            <div class="bg-white rounded-lg shadow mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-2xl font-bold text-gray-900"><?php echo e($ticket->title); ?></h3>
                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                        <span>Created <?php echo e($ticket->created_at->format('M d, Y H:i')); ?></span>
                        <span>•</span>
                        <span>Updated <?php echo e($ticket->updated_at->format('M d, Y H:i')); ?></span>
                    </div>
                </div>
                <div class="px-6 py-4">
                    <div class="prose max-w-none">
                        <p class="text-gray-700 whitespace-pre-wrap"><?php echo e($ticket->description); ?></p>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Comments</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <?php $__empty_1 = true; $__currentLoopData = $ticket->comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="border-l-4 <?php echo e($comment->is_internal ? 'border-yellow-400 bg-yellow-50' : 'border-blue-400 bg-blue-50'); ?> pl-4 py-3">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center space-x-2">
                                    <span class="font-semibold text-gray-900"><?php echo e($comment->user->name); ?></span>
                                    <?php if($comment->is_internal): ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-200 text-yellow-800">
                                            Internal Note
                                        </span>
                                    <?php endif; ?>
                                    <?php if($comment->user->isSupport()): ?>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-200 text-green-800">
                                            Support
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <span class="text-sm text-gray-500"><?php echo e($comment->created_at->format('M d, Y H:i')); ?></span>
                            </div>
                            <p class="text-gray-700 whitespace-pre-wrap"><?php echo e($comment->comment); ?></p>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-gray-500 text-center py-4">No comments yet.</p>
                    <?php endif; ?>
                </div>

                <!-- Add Comment Form -->
                <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                    <form method="POST" action="<?php echo e(route('tickets.comments.store', $ticket)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Add Comment</label>
                            <textarea name="comment"
                                      id="comment"
                                      rows="3"
                                      required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Write your comment here..."></textarea>
                        </div>

                        <?php if(auth()->user()->isSupport()): ?>
                        <div class="mb-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_internal" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-gray-700">Internal note (only visible to support staff)</span>
                            </label>
                        </div>
                        <?php endif; ?>

                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Add Comment
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Status</h4>
                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                    <?php if($ticket->status === 'new'): ?> bg-blue-100 text-blue-800
                    <?php elseif($ticket->status === 'in_progress'): ?> bg-yellow-100 text-yellow-800
                    <?php elseif($ticket->status === 'completed'): ?> bg-green-100 text-green-800
                    <?php elseif($ticket->status === 'closed'): ?> bg-gray-100 text-gray-800
                    <?php else: ?> bg-red-100 text-red-800
                    <?php endif; ?>">
                    <?php echo e(ucwords(str_replace('_', ' ', $ticket->status))); ?>

                </span>

                <?php if(auth()->user()->isSupport()): ?>
                <form method="POST" action="<?php echo e(route('tickets.status.update', $ticket)); ?>" class="mt-4">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Change Status</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md mb-2">
                        <?php $__currentLoopData = $statuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($status); ?>" <?php echo e($ticket->status === $status ? 'selected' : ''); ?>>
                                <?php echo e(ucwords(str_replace('_', ' ', $status))); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                        Update Status
                    </button>
                </form>
                <?php endif; ?>
            </div>

            <!-- Priority Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Priority</h4>
                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                    <?php if($ticket->priority === 'urgent'): ?> bg-red-100 text-red-800
                    <?php elseif($ticket->priority === 'high'): ?> bg-orange-100 text-orange-800
                    <?php elseif($ticket->priority === 'medium'): ?> bg-blue-100 text-blue-800
                    <?php else: ?> bg-gray-100 text-gray-800
                    <?php endif; ?>">
                    <?php echo e(ucfirst($ticket->priority)); ?>

                </span>
            </div>

            <!-- Category Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Category</h4>
                <p class="text-gray-900"><?php echo e($ticket->category->name); ?></p>
            </div>

            <!-- Requester Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Requester</h4>
                <p class="text-gray-900"><?php echo e($ticket->user->name); ?></p>
                <p class="text-sm text-gray-500"><?php echo e($ticket->user->email); ?></p>
            </div>

            <!-- Assignment Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Assigned To</h4>
                <?php if($ticket->assignedTo): ?>
                    <p class="text-gray-900"><?php echo e($ticket->assignedTo->name); ?></p>
                    <p class="text-sm text-gray-500"><?php echo e($ticket->assignedTo->email); ?></p>
                <?php else: ?>
                    <p class="text-gray-500 italic">Not assigned yet</p>
                <?php endif; ?>

                <?php if(auth()->user()->isSupport()): ?>
                <form method="POST" action="<?php echo e(route('tickets.assign', $ticket)); ?>" class="mt-4">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assign To</label>
                    <select name="assigned_to" id="assigned_to" class="w-full px-3 py-2 border border-gray-300 rounded-md mb-2">
                        <option value="">-- Unassigned --</option>
                        <?php $__currentLoopData = $supportUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e($ticket->assigned_to === $user->id ? 'selected' : ''); ?>>
                                <?php echo e($user->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                        Assign
                    </button>
                </form>
                <?php endif; ?>
            </div>

            <!-- Dates Card -->
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Dates</h4>
                <div class="space-y-2 text-sm">
                    <div>
                        <span class="text-gray-600">Created:</span>
                        <span class="text-gray-900"><?php echo e($ticket->created_at->format('M d, Y H:i')); ?></span>
                    </div>
                    <div>
                        <span class="text-gray-600">Updated:</span>
                        <span class="text-gray-900"><?php echo e($ticket->updated_at->format('M d, Y H:i')); ?></span>
                    </div>
                    <?php if($ticket->resolved_at): ?>
                    <div>
                        <span class="text-gray-600">Resolved:</span>
                        <span class="text-gray-900"><?php echo e($ticket->resolved_at->format('M d, Y H:i')); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Actions Card -->
            <?php if(auth()->user()->id === $ticket->user_id && $ticket->isNew() || auth()->user()->isAdmin()): ?>
            <div class="bg-white rounded-lg shadow p-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Actions</h4>
                <form method="POST" action="<?php echo e(route('tickets.destroy', $ticket)); ?>" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                        Delete Ticket
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\BARIŞ\Desktop\OOP\it_support_ticket_system\resources\views/tickets/show.blade.php ENDPATH**/ ?>