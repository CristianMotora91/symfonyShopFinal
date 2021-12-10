<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Product name must be at least {{ limit }} characters long",
     *      maxMessage = "Product name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @Assert\Range(
     *      min = 0,
     *      notInRangeMessage = "Price must be greater than {min}",
     *     )
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @Assert\Length(
     *      min = 2,
     *      max = 250,
     *      minMessage = "Product description must be at least {{ limit }} characters long",
     *      maxMessage = "Product name cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * *@Assert\Range(
     *      min = 0.1,
     *      notInRangeMessage = "Discount must be greater than {min}",
     *     )
     * @ORM\Column(type="integer", nullable=true)
     */
    private $discount;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\ManyToOne(targetEntity=Vendor::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $vendor;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="product", orphanRemoval=true)
     */
    private $images;

    /**
     * @Assert\All({
     * @Assert\File(
     *     maxSize = "2048k",
     *     mimeTypes = {"image/png"},
     *     mimeTypesMessage = "Please upload a valid Image"
     * ),
     * @Assert\Image(
     *     minWidth = 200,
     *     maxWidth = 4000,
     *     minHeight = 200,
     *     maxHeight = 4000
     * )
     * })
     */
    private $productImages;

    /**
     * @ORM\OneToMany(targetEntity=CartItem::class, mappedBy="product")
     */
    private $cartItems;

    /**
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="product", orphanRemoval=true)
     */
    private $orderItems;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->cartItems = new ArrayCollection();
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getVendor(): ?Vendor
    {
        return $this->vendor;
    }

    public function setVendor(?Vendor $vendor): self
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProduct($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getProduct() === $this) {
                $image->setProduct(null);
            }
        }

        return $this;
    }

    public function getFinalPrice()
    {
        $price = $this->price - $this->price*$this->discount/100;

        return $price;
    }

    function __toString()
    {
        return $this->name;
    }


    /**
     * @return Collection|CartItem[]
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): self
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems[] = $cartItem;
            $cartItem->setProduct($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): self
    {
        if ($this->cartItems->removeElement($cartItem)) {
            // set the owning side to null (unless already changed)
            if ($cartItem->getProduct() === $this) {
                $cartItem->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setProduct($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getProduct() === $this) {
                $orderItem->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getProductImages()
    {
        return $this->productImages;
    }

    /**
     * @param mixed $productImages
     * @return Product
     */
    public function setProductImages($productImages)
    {
        $this->productImages = $productImages;
        return $this;
    }



}
