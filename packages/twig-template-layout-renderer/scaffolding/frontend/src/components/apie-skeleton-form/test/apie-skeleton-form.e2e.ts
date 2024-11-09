import { newE2EPage } from '@stencil/core/testing';

describe('apie-skeleton-form', () => {
  it('renders', async () => {
    const page = await newE2EPage();
    await page.setContent('<apie-skeleton-form></apie-skeleton-form>');

    const element = await page.find('apie-skeleton-form');
    expect(element).toHaveClass('hydrated');
  });
});
