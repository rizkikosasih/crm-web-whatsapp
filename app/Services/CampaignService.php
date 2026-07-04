<?php

namespace App\Services;

use App\Repositories\Eloquent\CampaignRepository;
use App\Repositories\Eloquent\CustomerRepository;
use App\Services\Api\ImagekitServiceInterface;
use App\Services\Api\SendMessageApiServiceInterface;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class CampaignService
{
    protected $campaignRepository;
    protected $customerRepository;
    protected $imagekitService;
    protected $sendMessageApiService;

    public function __construct(
        CampaignRepository $campaignRepository,
        CustomerRepository $customerRepository,
        ImagekitServiceInterface $imagekitService,
        SendMessageApiServiceInterface $sendMessageApiService,
    ) {
        $this->campaignRepository = $campaignRepository;
        $this->customerRepository = $customerRepository;
        $this->imagekitService = $imagekitService;
        $this->sendMessageApiService = $sendMessageApiService;
    }

    /**
     * Get paginated campaigns.
     */
    public function getPaginated(int $perPage, ?string $search)
    {
        return $this->campaignRepository->getPaginated($perPage, $search);
    }

    /**
     * Find campaign.
     */
    public function find(int $id)
    {
        return $this->campaignRepository->find($id);
    }

    /**
     * Save campaign and upload image to ImageKit if provided.
     */
    public function save(array $data, $imageFile = null, ?int $id = null)
    {
        $campaign = $id ? $this->campaignRepository->find($id) : null;
        $imageLocalPath = $campaign ? $campaign->image : null;
        $imageUrl = $campaign ? $campaign->image_url : null;

        if ($imageFile instanceof TemporaryUploadedFile) {
            $filename = createFilename($data['title'], $imageFile->getClientOriginalExtension());

            // Save to local public storage
            $imageLocalPath = $imageFile->storeAs('images/campaigns', $filename, 'public');

            // Delete old images if exist
            if ($campaign && $campaign->image && $campaign->image !== $imageLocalPath) {
                Storage::disk('public')->delete($campaign->image);
            }
            if ($campaign && $campaign->image_url) {
                try {
                    $this->imagekitService->delete($campaign->image_url);
                } catch (\Exception $e) {
                    logger()->warning(
                        'Failed to delete old campaign image from ImageKit: ' . $e->getMessage(),
                    );
                }
            }

            // Upload new image to ImageKit
            $imageUrl = $this->imagekitService->upload($imageLocalPath, $filename, 'campaigns');
        }

        $saveData = [
            'title' => $data['title'],
            'message' => $data['message'],
            'image' => $imageLocalPath,
            'image_url' => $imageUrl,
            'created_by' => $data['created_by'],
        ];

        return $this->campaignRepository->createOrUpdate($saveData, $id);
    }

    /**
     * Broadcast campaign message to all registered customers.
     */
    public function broadcast(int $id)
    {
        $campaign = $this->find($id);
        $customers = $this->customerRepository->all();

        foreach ($customers as $customer) {
            $message = parseTemplatePlaceholders($campaign->message, [
                'name' => $customer->name,
                'contact_number' => config('app.contact') ?? '-',
                'store_name' => config('app.name') ?? '-',
                'image_url' => $campaign->image_url ?? '',
            ]);

            $this->sendMessageApiService->sendMessage(
                $customer->phone,
                $message,
                $campaign->image_url,
            );
        }

        return true;
    }
}
