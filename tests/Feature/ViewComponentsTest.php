<?php

namespace Tests\Feature;

use App\Models\User;
use App\View\Components\Dashboard\Sidebar;
use App\View\Components\Dashboard\TopNav;
use App\View\Components\Shared\Alert;
use App\View\Components\Shared\DangerButton;
use App\View\Components\Shared\Dropdown;
use App\View\Components\Shared\DropdownLink;
use App\View\Components\Shared\Header;
use App\View\Components\Shared\InputError;
use App\View\Components\Shared\InputLabel;
use App\View\Components\Shared\Link;
use App\View\Components\Shared\Modal;
use App\View\Components\Shared\NavLink;
use App\View\Components\Shared\PrimaryButton;
use App\View\Components\Shared\ResponsiveNavLink;
use App\View\Components\Shared\SecondaryButton;
use App\View\Components\Shared\TextInput;
use App\View\Components\Website\Footer;
use App\View\Components\Website\Navigation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewComponentsTest extends TestCase
{
    use RefreshDatabase;

public function test_dashboard_sidebar_component_renders(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $component = new Sidebar();
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.dashboard.sidebar', $view->name());
    }

public function test_dashboard_top_nav_component_renders(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $component = new TopNav();
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.dashboard.top-nav', $view->name());
    }

public function test_shared_alert_component_renders(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $component = new Alert('success');
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.shared.alert', $view->name());
    }

    public function test_shared_danger_button_component_renders(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $component = new DangerButton();
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.shared.danger-button', $view->name());
    }

    public function test_shared_dropdown_component_renders(): void
    {
        $component = new Dropdown();
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.shared.dropdown', $view->name());
    }

    public function test_shared_dropdown_link_component_renders(): void
    {
        $component = new DropdownLink();
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.shared.dropdown-link', $view->name());
    }

    public function test_shared_header_component_renders(): void
    {
        $component = new Header('page', 'Test Heading', 'Test subheading');
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.shared.header', $view->name());
    }

    public function test_shared_input_error_component_renders(): void
    {
        $component = new InputError();
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.shared.input-error', $view->name());
    }

    public function test_shared_input_label_component_renders(): void
    {
        $component = new InputLabel('test_for', 'Test Label');
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.shared.input-label', $view->name());
        $this->assertEquals('test_for', $component->for);
        $this->assertEquals('Test Label', $component->value);
    }

    public function test_shared_link_component_renders(): void
    {
        $component = new Link('test.route');
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.shared.link', $view->name());
        $this->assertEquals('test.route', $component->route);
    }

    public function test_shared_modal_component_renders(): void
    {
        $component = new Modal();
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.shared.modal', $view->name());
    }

    public function test_shared_nav_link_component_renders(): void
    {
        $component = new NavLink();
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.shared.nav-link', $view->name());
    }

    public function test_shared_primary_button_component_renders(): void
    {
        $component = new PrimaryButton();
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.shared.primary-button', $view->name());
    }

    public function test_shared_responsive_nav_link_component_renders(): void
    {
        $component = new ResponsiveNavLink();
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.shared.responsive-nav-link', $view->name());
    }

    public function test_shared_secondary_button_component_renders(): void
    {
        $component = new SecondaryButton();
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.shared.secondary-button', $view->name());
    }

    public function test_shared_text_input_component_renders(): void
    {
        $component = new TextInput();
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.shared.text-input', $view->name());
    }

    public function test_website_footer_component_renders(): void
    {
        $component = new Footer();
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.website.footer', $view->name());
    }

    public function test_website_navigation_component_renders(): void
    {
        $component = new Navigation();
        $view = $component->render();

        $this->assertInstanceOf('Illuminate\View\View', $view);
        $this->assertEquals('components.website.navigation', $view->name());
    }

    public function test_components_can_be_instantiated(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Test that all components can be instantiated without errors
        $components = [
            new Sidebar(),
            new TopNav(),
            new Alert('info'),
            new DangerButton(),
            new Dropdown(),
            new DropdownLink(),
            new Header('page', 'Test Heading', 'Test subheading'),
            new InputError(),
            new Modal(),
            new NavLink(),
            new PrimaryButton(),
            new ResponsiveNavLink(),
            new SecondaryButton(),
            new TextInput(),
            new Footer(),
            new Navigation(),
        ];

        foreach ($components as $component) {
            $this->assertNotNull($component);
            $view = $component->render();
            $this->assertInstanceOf('Illuminate\View\View', $view);
        }
    }

    public function test_components_with_parameters(): void
    {
        // Test components that require parameters
        $inputLabel = new InputLabel('email', 'Email Address');
        $this->assertEquals('email', $inputLabel->for);
        $this->assertEquals('Email Address', $inputLabel->value);

        $link = new Link('dashboard.index');
        $this->assertEquals('dashboard.index', $link->route);
    }

    public function test_header_component_with_different_parameters(): void
    {
        $header1 = new Header('dashboard', 'Dashboard', 'Welcome to your dashboard');
        $this->assertEquals('dashboard', $header1->type);
        $this->assertEquals('Dashboard', $header1->heading);
        $this->assertEquals('Welcome to your dashboard', $header1->subheading);
        $view1 = $header1->render();
        $this->assertInstanceOf('Illuminate\View\View', $view1);
        $this->assertEquals('components.shared.header', $view1->name());
        
        $header2 = new Header('auth', 'Login', 'Sign in to your account');
        $this->assertEquals('auth', $header2->type);
        $this->assertEquals('Login', $header2->heading);
        $this->assertEquals('Sign in to your account', $header2->subheading);
        $view2 = $header2->render();
        $this->assertInstanceOf('Illuminate\View\View', $view2);
        $this->assertEquals('components.shared.header', $view2->name());
    }
}
