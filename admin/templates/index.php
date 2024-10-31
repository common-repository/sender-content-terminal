<div class="wrap">
    <div id="main_ui">

        <h2>Content Terminal</h2>

        <div id="rss_pi_progressbar"></div>
        <div id="rss_pi_progressbar_label"></div>

        <form method="post" id="" enctype="multipart/form-data" action="">

            <input type="hidden" name="save_to_db" id="save_to_db" />

            <?php wp_nonce_field('settings_page', 'rss_pi_nonce'); ?>

            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-2">

                    <div id="postbox-container-1" class="postbox-container">

                    </div>

                    <div id="postbox-container-2" class="postbox-container">

                        <?php

                        ?>
                    </div>

                </div>
                <div class="clear">&nbsp;</div>
            </div>
        </form>

    </div>

    <div class="ajax_content"></div>


    <div class="postbox">
        <div class="inside">
            <div class="misc-pub-section">
                <ul>
                    <li>
                        <?php add_thickbox(); ?>
                        <div id="user-agreement-modal" style="display:none;">
                            <p class="mt-4 text-base">This plugin will import <b>Sender.Law</b> content as <b>posts</b> on your blog.</p>
                            <p class="mb-2 text-base">These posts may contain links other sites that our editorial
                                team believe provide valuable information.
                            </p>
                            <button id="content-terminal-button-accept" action="sender_content_terminal_accept_terms" class="mr-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Accept</button>
                            <button id="content-terminal-button-decline" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" onClick="tb_remove();">Decline</button>
                        </div>
                        <?php if (!$sender_content_terminal_accepted_terms) : ?>
                            <a id="content-terminal-agreement-link" href="#TB_inline?width=600&height=200&inlineId=user-agreement-modal&title=UserAgreement" name="User Agreement" class="thickbox text-base">
                                <svg fill="currentColor" viewBox="0 0 20 20" class="h-6 float-left mr-2">
                                    <path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" fill-rule="evenodd"></path>
                                </svg>Please Accept the user agreement. Click here to view.
                            </a>
                            <input type="hidden" id="content-terminal-user-has-not-accepted" />
                        <?php endif; ?>

                        <?php add_thickbox(); ?>
                        <div id="add-token-modal" style="display:none;">
                            <p>Your token is located in your Sender.law settings page.</p>
                            <form action="sender_content_terminal_save_token" id="sender_content_terminal_save_token">
                                <div class=" mt-4">
                                    <div>
                                        <label class="text-gray-700">Token</label>
                                        <input type="text" name="plugin_token" value="<?php echo $sender_content_terminal_token; ?>" class="plugin_token w-full mt-1 px-4 py-2 block rounded bg-gray-200 text-gray-800 border border-gray-300 focus:outline-none focus:bg-white" required>
                                    </div>
                                </div>
                                <div class="flex justify-start mt-4">
                                    <button class="px-4 py-2 bg-gray-800 text-gray-200 rounded hover:bg-gray-700 focus:outline-none focus:bg-gray-700">
                                        Save Token
                                    </button>
                                </div>
                            </form>
                        </div>
                        <?php if (!$sender_content_terminal_token && $sender_content_terminal_accepted_terms) : ?>
                            <a id="content-terminal-agreement-link" href="#TB_inline?width=600&height=200&inlineId=add-token-modal&title=PluginToken" name="User Agreement" class="thickbox text-base">
                                <svg fill="currentColor" viewBox="0 0 20 20" class="h-6 float-left mr-2">
                                    <path d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" fill-rule="evenodd"></path>
                                </svg>Your Plugin token is missing click here to add.
                            </a>
                            <input type="hidden" id="content-terminal-token-missing" />
                        <?php endif; ?>

                        <?php if ($sender_content_terminal_token && $sender_content_terminal_accepted_terms) : ?>

                            <div class="flex max-w-md mx-auto bg-white rounded-lg overflow-hidden shadow-md">
                                <div class="flex justify-center items-center w-20 bg-green-500"><svg viewBox="0 0 40 40" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 fill-current text-white">
                                        <path d="M20 3.33331C10.8 3.33331 3.33337 10.8 3.33337 20C3.33337 29.2 10.8 36.6666 20 36.6666C29.2 36.6666 36.6667 29.2 36.6667 20C36.6667 10.8 29.2 3.33331 20 3.33331ZM16.6667 28.3333L8.33337 20L10.6834 17.65L16.6667 23.6166L29.3167 10.9666L31.6667 13.3333L16.6667 28.3333Z"></path>
                                    </svg></div>
                                <div class="-mx-3 py-2 px-4">
                                    <div class="mx-3"><span class="text-green-500 font-semibold">Success</span>
                                        <p class="text-gray-600 text-sm">Your content terminal is connected. To manage your settings login to your <a href="https://sender.law/newsletter/v3/settings#panel5" class="font-bold">Sender.law</a> account.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-10">
                                <a id="content-terminal-agreement-link" href="#TB_inline?width=600&height=200&inlineId=add-token-modal&title=PluginToken" name="Plugin Token" class="thickbox text-lg text-gray-700">
                                    <svg fill="currentColor" viewBox="0 0 20 20" class="h-6 inline">
                                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                                    </svg> Modify Plugin Token
                                </a>
                            </div>
                        <?php endif; ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>