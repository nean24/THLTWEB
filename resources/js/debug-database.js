// Test script to debug Supabase connection and tables
// Run this in browser console to test database connectivity

async function testDatabase() {
  console.log('=== TESTING SUPABASE CONNECTION ===')

  // Test 1: Check if supabase client works
  try {
    const { data: { user } } = await supabase.auth.getUser()
    console.log('✅ Auth working, user:', user?.email || 'Not logged in')
  } catch (error) {
    console.error('❌ Auth error:', error)
  }

  // Test 2: Check posts table
  try {
    const { data: posts, error } = await supabase
      .from('posts')
      .select('id, content, created_at')
      .limit(1)

    if (error) throw error
    console.log('✅ Posts table working, sample:', posts?.[0])
  } catch (error) {
    console.error('❌ Posts table error:', error)
  }

  // Test 3: Check comments table structure
  try {
    const { data: comments, error } = await supabase
      .from('comments')
      .select('*')
      .limit(1)

    if (error) throw error
    console.log('✅ Comments table working, sample:', comments?.[0])

    if (comments?.length > 0) {
      const comment = comments[0]
      console.log('Comment fields:', Object.keys(comment))
    }
  } catch (error) {
    console.error('❌ Comments table error:', error)
  }

  // Test 4: Check profiles table
  try {
    const { data: profiles, error } = await supabase
      .from('profiles')
      .select('id, username, display_name, avatar_url')
      .limit(1)

    if (error) throw error
    console.log('✅ Profiles table working, sample:', profiles?.[0])
  } catch (error) {
    console.error('❌ Profiles table error:', error)
  }

  // Test 5: Try inserting a test comment (if logged in)
  try {
    const { data: { user } } = await supabase.auth.getUser()
    if (user) {
      // First get a post ID
      const { data: posts } = await supabase
        .from('posts')
        .select('id')
        .limit(1)

      if (posts?.length > 0) {
        const { data, error } = await supabase
          .from('comments')
          .insert({
            post_id: posts[0].id,
            user_id: user.id,
            content: 'Test comment from debug script'
          })
          .select()

        if (error) throw error
        console.log('✅ Comment insertion working, result:', data)

        // Clean up test comment
        if (data?.length > 0) {
          await supabase
            .from('comments')
            .delete()
            .eq('id', data[0].id)
          console.log('✅ Test comment cleaned up')
        }
      }
    }
  } catch (error) {
    console.error('❌ Comment insertion error:', error)
  }

  console.log('=== TEST COMPLETE ===')
}

// Call the test function
testDatabase()
