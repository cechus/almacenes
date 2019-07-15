<?php

namespace App\Observers;

use App\ArticleIncomeItem;
use Log;
class ArticleIncomeItemObserver
{
    /**
     * Handle the article income item "created" event.
     *
     * @param  \App\ArticleIncomeItem  $articleIncomeItem
     * @return void
     */
    public function created(ArticleIncomeItem $articleIncomeItem)
    {
        //
        Log::info("article_income_item:  created ");//user el metodo explicitamente con el saved

    }

    /**
     * Handle the article income item "updated" event.
     *
     * @param  \App\ArticleIncomeItem  $articleIncomeItem
     * @return void
     */
    public function updated(ArticleIncomeItem $articleIncomeItem)
    {
        //
        Log::info("article_income_item: updated");
    }

    /**
     * Handle the article income item "deleted" event.
     *
     * @param  \App\ArticleIncomeItem  $articleIncomeItem
     * @return void
     */
    public function deleted(ArticleIncomeItem $articleIncomeItem)
    {
        //
        Log::info("article_income_item: deleted");
    }

    /**
     * Handle the article income item "restored" event.
     *
     * @param  \App\ArticleIncomeItem  $articleIncomeItem
     * @return void
     */
    public function restored(ArticleIncomeItem $articleIncomeItem)
    {
        //
        Log::info("article_income_item: restored");
    }

    /**
     * Handle the article income item "force deleted" event.
     *
     * @param  \App\ArticleIncomeItem  $articleIncomeItem
     * @return void
     */
    public function forceDeleted(ArticleIncomeItem $articleIncomeItem)
    {
        //
        Log::info("article_income_item: forceDeleted");
    }
}
